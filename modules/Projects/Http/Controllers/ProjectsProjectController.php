<?php
namespace Modules\Projects\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Traits\Contacts;
use App\Traits\Currencies;
use App\Traits\DateTime;
use Auth;
use Date;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Projects\Exports\TransactionsExport;
use Modules\Projects\Http\Requests\ProjectRequest;
use Modules\Projects\Models\Activity;
use Modules\Projects\Models\Discussion;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\ProjectUser;
use Modules\Projects\Models\Task;

class ProjectsProjectController extends Controller
{
    use Currencies, DateTime, Contacts;

    public $today;

    public $financial_start;

    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-projects')->only([
            'create',
            'store',
            'duplicate',
            'import',
        ]);
        $this->middleware('permission:read-projects')->only([
            'index',
            'show',
            'edit',
            'export',
        ]);
        $this->middleware('permission:update-projects')->only([
            'update',
            'enable',
            'disable',
        ]);
        $this->middleware('permission:delete-projects')->only('destroy');
    }

    public function index(Request $request)
    {
        $projects = Project::all();

        return view('projects::projects.index', compact('projects'));
    }

    public function create()
    {
        $customers = Contact::type($this->getCustomerTypes())->pluck('name', 'id');
        $users = Company::find(session('company_id'))->users()->pluck('name', 'id');

        return view('projects::projects.create', compact('customers', 'users'));
    }

    public function store(ProjectRequest $request)
    {
        $project = new Project();
        $project->company_id = session('company_id');
        $project->name = $request->name;
        $project->description = $request->description;
        $project->customer_id = $request->customer_id;
        $project->started_at = $request->started_at;
        $project->ended_at = $request->ended_at;
        $project->status = 0;

        $project->save();

        $members = request('members', array());

        if (!in_array(Auth::id(), $members)) {
            array_push($members, Auth::id());
        }

        foreach ($members as $member) {
            ProjectUser::create([
                'company_id' => session('company_id'),
                'project_id' => $project->id,
                'user_id' => $member,
            ]);
        }

        $message = trans('projects::general.success');

        flash($message)->success();

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('projects.projects.index'),
        ];

        return response()->json($response);
    }

    public function show(Project $project, Request $request)
    {
        $this->today = Date::today();
        $this->financial_start = $financial_start = $this->getFinancialStart()->format('Y-m-d');
        $itemLimits = $this->getLimits();
        $status = $this->getStatus();
        $doc_type = $this->getDocStatus();
        $activetab = request('activetab', 'overview');
        $members = Company::find(session('company_id'))->users()->pluck('name', 'id');

        $transactionLimit = request('limit', array_values($this->getLimits())[1]);
        $taskLimit = request('taskLimit', array_values($this->getLimits())[1]);
        $discussionLimit = request('taskLimit', array_values($this->getLimits())[1]);

        $overview = $this->prepareDataForOverviewTab($project);
        $activities = $this->prepareDataForActivitiesTab($project);
        $transactions = $this->prepareDataForTransactionsTab($project, $transactionLimit);
        $tasks = $this->prepareDataForTasksTab($project, $taskLimit);
        $discussions = $this->prepareDataForDiscussionsTab($project, $discussionLimit);

        $date_format = $this->getCompanyDateFormat();

        return view('projects::projects.show', compact('project', 'activities', 'overview', 'transactions', 'tasks', 'financial_start', 'itemLimits', 'status', 'activetab', 'doc_type', 'request', 'discussions', 'date_format', 'members'));
    }

    public function edit(Project $project)
    {
        $customers = Contact::type($this->getCustomerTypes())->pluck('name', 'id');
        $statuses = (object) $this->getStatus();
        $users = Company::find(session('company_id'))->users()->pluck('name', 'id');
        $members = Company::find(session('company_id'))->users()
            ->whereIn('id', $project->users()
                    ->pluck('user_id'))
            ->pluck('id');

        foreach ($members as $key => $value) {
            $members[$key] = strval($value);
        }

        return view('projects::projects.edit', compact('customers', 'project', 'statuses', 'users', 'members'));
    }

    public function update(Project $project, Request $request)
    {
        $project->company_id = session('company_id');
        $project->name = $request->name;
        $project->customer_id = $request->customer_id;
        $project->description = $request->description;
        $project->started_at = $request->started_at;
        $project->ended_at = $request->ended_at;
        $project->status = request('status');
        $project->update();

        $allMembers = ProjectUser::where([
            'company_id' => session('company_id'),
            'project_id' => $project->id,
        ])->get()
            ->pluck('user_id')
            ->toArray();

        $members = request('members');

        foreach ($allMembers as $member) {

            if (!in_array($member, $members)) {
                ProjectUser::where([
                    'company_id' => session('company_id'),
                    'project_id' => $project->id,
                    'user_id' => $member,
                ])->delete();
            }
        }

        foreach ($members as $newMember) {
            if (!in_array($newMember, $allMembers)) {
                ProjectUser::create([
                    'company_id' => session('company_id'),
                    'project_id' => $project->id,
                    'user_id' => $newMember,
                ]);
            }
        }

        $message = trans('messages.success.updated', [
            'type' => trans_choice('projects::general.project', 1),
        ]);

        flash($message)->success();

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('projects.projects.index'),
        ];

        return response()->json($response);
    }

    public function destroy(Project $project)
    {
        $project->tasks()->each(function ($task) {
            $task->subtasks()
                ->each(function ($subtask) {
                    $subtask->users()
                        ->delete();
                });

            $task->subtasks()
                ->delete();
        });

        $project->tasks()->delete();

        $project->discussions()->each(function ($discussion) {
            $discussion->comments()
                ->delete();
            $discussion->likes()
                ->delete();
        });

        $project->discussions()->delete();
        $project->bills()->delete();
        $project->invoices()->delete();
        $project->payments()->delete();
        $project->revenues()->delete();
        $project->users()->delete();
        $project->delete();

        $message = trans('messages.success.deleted', [
            'type' => trans_choice('projects::general.project', 1),
        ]);

        flash($message)->success();

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('projects.projects.index'),
        ];

        return response()->json($response);
    }

    public function printProject(Project $project, Request $request)
    {
        $date_format = $this->getCompanyDateFormat();
        $transactions = $this->prepareDataForTransactionsTab($project, 999999);
        $profitMarginOfProject = $this->calculateProfitMargin($project);

        return view('projects::projects.print', compact('transactions', 'project', 'profitMarginOfProject', 'date_format'));
    }

    public function exportTransactions(Project $project)
    {
        return Excel::download(new TransactionsExport($project), 'transactions.xlsx');
    }

    private function getStatus()
    {
        $status = [
            trans('projects::general.inprogress'),
            trans('projects::general.completed'),
            trans('projects::general.canceled'),
        ];

        return $status;
    }

    private function getDocStatus()
    {
        $docStatus = [
            '0' => trans_choice('general.invoices', 1),
            '1' => trans_choice('general.revenues', 1),
            '2' => trans_choice('general.bills', 1),
            '3' => trans_choice('general.payments', 1),
        ];

        return $docStatus;
    }

    private function getLimits()
    {
        $limits = [
            '12' => '12',
            '24' => '24',
            '48' => '48',
            '96' => '96',
        ];

        return $limits;
    }

    private function getPriorityList()
    {
        $priorities = [
            '0' => trans('projects::general.low'),
            '1' => trans('projects::general.medium'),
            '2' => trans('projects::general.high'),
            '3' => trans('projects::general.urgent'),
        ];

        return $priorities;
    }

    private function getTaskStatusList()
    {
        $taskStatusList = [
            '0' => trans('projects::general.notstarted'),
            '1' => trans('projects::general.active'),
            '2' => trans('projects::general.completed'),
        ];

        return $taskStatusList;
    }

    private function prepareDataForOverviewTab(Project $project)
    {
        $invoices = array(
            'totalInvoice' => $this->getInvoiceCount($project),
            'totalInvoiceAmount' => $this->getInvoiceTotalAmount($project),
        );

        $revenues = array(
            'totalRevenue' => $this->getRevenueCount($project),
            'totalRevenueAmount' => $this->getRevenueTotalAmount($project),
        );

        $bills = array(
            'totalBill' => $this->getBillCount($project),
            'totalBillAmount' => $this->getBillTotalAmount($project),
        );

        $payments = array(
            'totalPayment' => $this->getPaymentCount($project),
            'totalPaymentAmount' => $this->getPaymentTotalAmount($project),
        );

        $tasks = array(
            'totalTask' => $project->tasks->count(),
        );

        $discussions = array(
            'totalDiscussion' => $project->discussions->count(),
        );

        $activities = array(
            'totalActivity' => $project->activities->count(),
        );

        $users = array(
            'totalUser' => $project->users->count(),
        );

        $activeDiscussions = $this->getActiveDiscussions($project);
        $recentlyAddedTasks = $this->getRecentlyAddedTasks($project);

        return array(
            'invoices' => $invoices,
            'revenues' => $revenues,
            'bills' => $bills,
            'payments' => $payments,
            'tasks' => $tasks,
            'discussions' => $discussions,
            'activities' => $activities,
            'users' => $users,
            'activeDiscussions' => $activeDiscussions,
            'recentlyAddedTasks' => $recentlyAddedTasks,
        );
    }

    private function prepareDataForTransactionsTab(Project $project, $transactionLimit)
    {
        $transactionList = $this->getFinancials($project);

        return array(
            'transactionLimit' => $transactionLimit,
            'transactionList' => $transactionList,
        );
    }

    private function prepareDataForTasksTab(Project $project, $taskLimit)
    {
        $taskPriorities = $this->getPriorityList();
        $taskStatusList = $this->getTaskStatusList();
        $users = Company::find(session('company_id'))->users()->pluck('name', 'id');
        $taskList = $project->tasks()->orderBy('created_at', 'desc')->get();

        foreach ($taskList as $task) {
            foreach ($task->subtasks as $subtask) {
                foreach ($taskPriorities as $key => $value) {
                    if ($key == $subtask->priority) {
                        $subtask['priority_text'] = $value;
                    }
                }

                foreach ($taskStatusList as $key => $value) {
                    if ($key == $subtask->status) {
                        $subtask['status_text'] = $value;
                    }
                }

                foreach ($subtask->users as $user) {
                    $user['username'] = User::where('id', $user->user_id)->first()->name;

                    if (User::where('id', $user->user_id)->first()->picture) {
                        if (setting('general.use_gravatar', '0') == '1') {
                            $user['picture'] = '<img src="' + User::where('id', $user->user_id)->first()->picture + '" class="img-circle img-sm" alt="User Image">';
                        } else {
                            $user['picture'] = '<img src="' + Storage::url(User::where('id', $user->user_id)->first()->picture->id) + '" class="img-circle img-sm" alt="User Image">';
                        }
                    } else {
                        $user['picture'] = '<i class="fas fa-user"></i>';
                    }
                }

                $subtask['users'] = $subtask->$users;
            }

            $task['subtasks'] = $task->subtasks;
        }

        return array(
            'taskLimit' => $taskLimit,
            'taskList' => $taskList,
            'taskPriorities' => $taskPriorities,
            'taskStatusList' => $taskStatusList,
            'taskUsers' => $users,
        );
    }

    private function prepareDataForDiscussionsTab(Project $project, $discussionLimit)
    {
        $i = 0;
        $discussionList = array();

        foreach ($project->discussions as $discussion) {
            $discussionList[$i] = array(
                'id' => $discussion->id,
                'name' => $discussion->name,
                'description' => $discussion->description,
                'total_like' => $discussion->total_like,
                'total_comment' => $discussion->total_comment,
                'last_activity' => Date::parse($discussion->updated_at)->format($this->getCompanyDateFormat()),
                'updated_at' => $discussion->updated_at,
                'created_by' => User::where('id', $discussion->created_by)->first()->name,
            );

            $i++;
        }

        return array(
            'discussionLimit' => $discussionLimit,
            'discussionList' => $discussionList,
        );
    }

    private function prepareDataForActivitiesTab(Project $project)
    {
        $activities = Activity::where('project_id', $project->id)->get()->sortByDesc('created_at');

        $i = 0;
        $previousCreated = null;
        $activitiesArray = array();

        foreach ($activities as $activity) {
            $activity['created'] = Date::parse($activity->created_at)->format($this->getCompanyDateFormat());
            $activity['created_time'] = Date::parse($activity->created_at)->format("H:m");

            if ($previousCreated != $activity['created']) {
                $previousCreated = $activity['created'];

                $activitiesArray[$i] = array(
                    'activity_type' => 'NewLabel',
                    'created_at' => $activity['created_at'],
                    'description' => $activity['description'],
                    'created' => $activity['created'],
                    'created_time' => $activity['created_time'],
                );

                $i++;
            }

            $activitiesArray[$i] = array(
                'activity_type' => $activity['activity_type'],
                'created_at' => $activity['created_at'],
                'description' => $activity['description'],
                'created' => $activity['created'],
                'created_time' => $activity['created_time'],
            );

            $i++;
        }

        return array(
            'allActivities' => $activitiesArray,
        );
    }

    private function getInvoiceTotalAmount(Project $project)
    {
        $total = 0;

        Invoice::accrued()->whereIn('id', $project->invoices->pluck('invoice_id'))
            ->each(function ($item, $key) use (&$total) {
                $total += $item->getAmountConvertedToDefault();
            });

        return $total;
    }

    private function getInvoiceCount(Project $project)
    {
        return Invoice::accrued()->whereIn('id', $project->invoices->pluck('invoice_id'))
            ->get()
            ->count();
    }

    private function getRevenueTotalAmount(Project $project)
    {
        $total = 0;

        Transaction::whereIn('id', $project->revenues->pluck('revenue_id'))->each(function ($item, $key) use (&$total) {
            $total += $item->getAmountConvertedToDefault();
        });

        return $total;
    }

    private function getRevenueCount(Project $project)
    {
        Transaction::whereIn('id', $project->revenues->pluck('revenue_id'))->get()->count();
    }

    private function getBillTotalAmount(Project $project)
    {
        $total = 0;

        Bill::accrued()->whereIn('id', $project->bills->pluck('bill_id'))
            ->each(function ($item, $key) use (&$total) {
                $total += $item->getAmountConvertedToDefault();
            });

        return $total;
    }

    private function getBillCount(Project $project)
    {
        return Bill::accrued()->whereIn('id', $project->bills->pluck('bill_id'))
            ->get()
            ->count();
    }

    private function getPaymentCount(Project $project)
    {
        Transaction::whereIn('id', $project->payments->pluck('payment_id'))->get()->count();
    }

    private function getPaymentTotalAmount(Project $project)
    {
        $total = 0;

        Transaction::whereIn('id', $project->payments->pluck('payment_id'))->each(function ($item, $key) use (&$total) {
            $total += $item->getAmountConvertedToDefault();
        });

        return $total;
    }

    private function calculateProfitMargin(Project $project)
    {
        $profitMargin = 0;
        $totalInvoiceAmount = $this->getInvoiceTotalAmount($project);
        $totalRevenueAmount = $this->getRevenueTotalAmount($project);
        $totalBillAmount = $this->getBillTotalAmount($project);
        $totalPaymentAmount = $this->getPaymentTotalAmount($project);

        if ($totalInvoiceAmount + $totalRevenueAmount > 0) {
            $profitMargin = ($totalInvoiceAmount + $totalRevenueAmount - $totalBillAmount - $totalPaymentAmount) / ($totalInvoiceAmount + $totalRevenueAmount) * 100;
        }

        $profitMargin = number_format($profitMargin, 2, '.', '');

        return $profitMargin;
    }

    private function calculateProfitNet(Project $project)
    {
        $profitNet = 0;
        $totalInvoiceAmount = $this->getInvoiceTotalAmount($project);
        $totalRevenueAmount = $this->getRevenueTotalAmount($project);
        $totalBillAmount = $this->getBillTotalAmount($project);
        $totalPaymentAmount = $this->getPaymentTotalAmount($project);

        $profitNet = $totalInvoiceAmount + $totalRevenueAmount - $totalBillAmount - $totalPaymentAmount;

        $profitNet = number_format($profitNet, 2);

        return $profitNet;
    }

    private function calculateProfitLossIncomeTotals($start, $end, $period, Project $project)
    {
        $totals = array();

        $m1 = '\App\Models\Income\InvoicePayment';
        $m2 = '\App\Models\Income\Revenue';

        $date_format = 'Y-m';

        if ($period == 'month') {
            $n = 1;
            $start_date = $start->format($date_format);
            $end_date = $end->format($date_format);
            $next_date = $start_date;
        } else {
            $n = 3;
            $start_date = $start->quarter;
            $end_date = $end->quarter;
            $next_date = $start_date;
        }

        $s = clone $start;

        while ($next_date <= $end_date) {
            $totals[$next_date] = 0;

            if ($period == 'month') {
                $next_date = $s->addMonths($n)->format($date_format);
            } else {
                if (isset($totals[4])) {
                    break;
                }

                $next_date = $s->addMonths($n)->quarter;
            }
        }

        $items_1 = $m1::whereIn('invoice_id', $project->invoices->pluck('invoice_id'))->whereBetween('paid_at', [
            $start,
            $end,
        ])->get();

        $this->setProfitLossTotals($totals, $items_1, $date_format, $period);

        $items_2 = $m2::whereIn('id', $project->revenues->pluck('revenue_id'))->whereBetween('paid_at', [
            $start,
            $end,
        ])
            ->isNotTransfer()
            ->get();

        $this->setProfitLossTotals($totals, $items_2, $date_format, $period);

        return $totals;
    }

    private function calculateProfitLossExpenseTotals($start, $end, $period, Project $project)
    {
        $totals = array();

        $m1 = '\App\Models\Expense\BillPayment';
        $m2 = '\App\Models\Expense\Payment';

        $date_format = 'Y-m';

        if ($period == 'month') {
            $n = 1;
            $start_date = $start->format($date_format);
            $end_date = $end->format($date_format);
            $next_date = $start_date;
        } else {
            $n = 3;
            $start_date = $start->quarter;
            $end_date = $end->quarter;
            $next_date = $start_date;
        }

        $s = clone $start;

        while ($next_date <= $end_date) {
            $totals[$next_date] = 0;

            if ($period == 'month') {
                $next_date = $s->addMonths($n)->format($date_format);
            } else {
                if (isset($totals[4])) {
                    break;
                }

                $next_date = $s->addMonths($n)->quarter;
            }
        }

        $items_1 = $m1::whereIn('bill_id', $project->bills->pluck('bill_id'))->whereBetween('paid_at', [
            $start,
            $end,
        ])->get();

        $this->setProfitLossTotals($totals, $items_1, $date_format, $period);

        $items_2 = $m2::whereIn('id', $project->payments->pluck('payment_id'))->whereBetween('paid_at', [
            $start,
            $end,
        ])
            ->isNotTransfer()
            ->get();

        $this->setProfitLossTotals($totals, $items_2, $date_format, $period);

        return $totals;
    }

    private function setProfitLossTotals(&$totals, $items, $date_format, $period)
    {
        foreach ($items as $item) {
            if ($period == 'month') {
                $i = Date::parse($item->paid_at)->format($date_format);
            } else {
                $i = Date::parse($item->paid_at)->quarter;
            }

            if (!isset($totals[$i])) {
                continue;
            }

            $totals[$i] += $item->getAmountConvertedToDefault();
        }
    }

    private function calculateProfitLoss($incomes, $expenses)
    {
        $profit = [];

        foreach ($incomes as $key => $income) {
            $profit[$key] = $income - $expenses[$key];
        }

        return $profit;
    }

    public function profitLoss(Project $project)
    {
        $this->today = Date::today();

        $content = '';

        echo $content;
    }

    private function getLatestTransactions(Project $project)
    {
        $invoices = collect(Invoice::whereIn('id', $project->invoices->pluck('invoice_id'))->orderBy('invoiced_at', 'desc')
                ->take(5)
                ->get())->each(function ($invoice) {
            $invoice->paid_at = $invoice->invoiced_at;
        });

        $revenues = collect(Transaction::whereIn('id', $project->revenues->pluck('revenue_id'))->orderBy('paid_at', 'desc')
                ->isNotTransfer()
                ->take(5)
                ->get());

        $bills = collect(Bill::whereIn('id', $project->bills->pluck('bill_id'))->orderBy('billed_at', 'desc')
                ->take(5)
                ->get())->each(function ($bill) {
            $bill->paid_at = $bill->billed_at;
        });

        $payments = collect(Transaction::whereIn('id', $project->payments->pluck('payment_id'))->orderBy('paid_at', 'desc')
                ->isNotTransfer()
                ->take(5)
                ->get());

        $latest = $revenues->merge($invoices)
            ->merge($bills)
            ->merge($payments)
            ->take(5)
            ->sortByDesc('paid_at');

        return $latest;
    }

    private function getActiveDiscussions(Project $project)
    {
        $discussions = Discussion::where('project_id', $project->id)->latest()
            ->get()
            ->take(5);

        return $discussions;
    }

    private function getRecentlyAddedTasks(Project $project)
    {
        $tasks = Task::where('project_id', $project->id)->get()
            ->sortByDesc('created_at')
            ->take(5);

        return $tasks;
    }

    private function getFinancials(Project $project)
    {
        $invoices = collect(Invoice::whereIn('id', $project->invoices->pluck('invoice_id'))->orderBy('invoiced_at', 'desc')->get())->each(function ($invoice) {
            $invoice->paid_at = $invoice->invoiced_at;
        });

        $revenues = collect(Transaction::whereIn('id', $project->revenues->pluck('revenue_id'))->orderBy('paid_at', 'desc')
                ->isNotTransfer()
                ->get());

        $bills = collect(Bill::whereIn('id', $project->bills->pluck('bill_id'))->orderBy('billed_at', 'desc')->get())->each(function ($bill) {
            $bill->paid_at = $bill->billed_at;
        });

        $payments = collect(Transaction::whereIn('id', $project->payments->pluck('payment_id'))->orderBy('paid_at', 'desc')
                ->isNotTransfer()
                ->get());

        $financials = $invoices->merge($revenues)
            ->merge($bills)
            ->merge($payments);

        $i = 0;
        $transactionsArray = array();

        foreach ($financials as $item) {
            if (!empty($item->category)) {
                $category_name = ($item->category) ? $item->category->name : trans('general.na');
            } else {
                $category_name = trans('general.na');
            }

            if ($item['invoice_number'] || $item['bill_number']) {
                $item['description'] = $item['notes'];
            }

            if ($item['invoice_number']) {
                $item['type'] = trans_choice('general.invoices', 1);
            } elseif ($item['bill_number']) {
                $item['type'] = trans_choice('general.bills', 1);
            } elseif ($item['type'] === 'income') {
                $item['type'] = trans_choice('general.revenues', 1);
            } elseif ($item['type'] === 'expense') {
                $item['type'] = trans_choice('general.payments', 1);
            }

            $item['category_name'] = $category_name;
            $item['transaction_at'] = Date::parse($item->paid_at)->format($this->getCompanyDateFormat());
            $item['transaction_amount'] = money($item->amount, $item->currency_code, true)->format();

            $transactionsArray[$i] = array(
                'paid_at' => $item['paid_at'],
                'amount' => $item['amount'],
                'category_name' => $item['category_name'],
                'transaction_at' => $item['transaction_at'],
                'transaction_amount' => $item['transaction_amount'],
                'description' => $item['description'],
                'type' => $item['type'],
            );

            $i++;
        }

        return $transactionsArray;
    }
}
