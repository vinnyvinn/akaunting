<?php

namespace Modules\Crm\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Common\Company;
use App\Traits\DateTime;
use Modules\Crm\Exports\Activities\Activities as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use Modules\Crm\Imports\Activities\Activities as Import;
use Modules\Crm\Models\Email;
use Modules\Crm\Models\Log;
use Modules\Crm\Models\Note;
use Modules\Crm\Models\Schedule;
use Modules\Crm\Models\Task;
use Modules\Crm\Models\DealActivity;

use Modules\Crm\Traits\Activities as ActivitiesTrait;

class Activities extends Controller
{
    use ActivitiesTrait, DateTime;


    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-crm-deals')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-crm-deals')->only(['index', 'show', 'edit', 'export']);

    }

    public function index()
    {
        $note = $task = $email = $log = $schedule = $deal_activity = null;

        $filter_type = request('type', 'all');

        $filter_user = request('user', 'all');

        switch ($filter_type) {
            case 'task':
                if ($filter_user == 'all')
                    $task = Task::all();
                else
                    $task = Task::where('created_by', $filter_user)->get();
                break;
            case 'note':
                if ($filter_user == 'all')
                    $note = Note::all();
                else
                    $note = Note::where('created_by', $filter_user)->get();
                break;
            case 'schedule':
                if ($filter_user == 'all')
                    $schedule = Schedule::all();
                else
                    $schedule = Schedule::where('created_by', $filter_user)->get();
                break;
            case 'email':
                if ($filter_user == 'all')
                    $email = Email::all();
                else
                    $email = Email::where('created_by', $filter_user)->get();
                break;
            case 'log':
                if ($filter_user == 'all')
                    $log = Log::all();
                else
                    $log = Log::where('created_by', $filter_user)->get();
                break;
            case 'deal_activities':
                if ($filter_user == 'all')
                    $deal_activity = DealActivity::all();
                else
                    $deal_activity = DealActivity::where('created_by', $filter_user)->get();
                break;
            case 'all':
                if ($filter_user == 'all') {
                    $note = Note::all();
                    $task = Task::all();
                    $email = Email::all();
                    $log = Log::all();
                    $schedule = Schedule::all();
                    $deal_activity = DealActivity::all();
                } else {
                    $note = Note::where('created_by', $filter_user)->get();
                    $task = Task::where('created_by', $filter_user)->get();
                    $email = Email::where('created_by', $filter_user)->get();
                    $log = Log::where('created_by', $filter_user)->get();
                    $schedule = Schedule::where('created_by', $filter_user)->get();
                    $deal_activity = DealActivity::where('created_by', $filter_user)->get();
                }
                break;
        }

        $items = (object) array_merge_recursive((array) $note, (array) $task, (array) $email, (array) $log, (array) $schedule, (array) $deal_activity);

        $activities = $this->getTimelineData($items, true);

        $types = $this->getActivityTypes();

        $users = Company::find(session('company_id'))->users()->pluck('name', 'id');
        $users->prepend(trans('general.all'), 'all');

        return view('crm::activities.index', compact('activities', 'users', 'types'));
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest  $request
     *
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        \Excel::import(new Import(), $request->file('import'));

        $message = trans('messages.success.imported', ['type' => trans_choice('crm::general.activities', 2)]);

        flash($message)->success();

        return redirect()->route('crm.activities.index');
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return \Excel::download(new Export(), trans_choice('crm::general.activities', 2) . '.xlsx');
    }
}
