<?php

namespace Modules\DoubleEntry\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Currency;
use Date;
use Modules\DoubleEntry\Http\Requests\JournalItem as IRequest;
use Modules\DoubleEntry\Models\Account;
use Modules\DoubleEntry\Models\Journal;
use Modules\DoubleEntry\Models\Ledger;
use Modules\DoubleEntry\Models\Type;
use Modules\DoubleEntry\Http\Requests\Journal as Request;
use Illuminate\Http\Request as ItemRequest;
use App\Traits\DateTime;


class JournalEntry extends Controller
{
    use DateTime;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $limit = request('limit', setting('default.list_limit', '25'));

        if (empty(request()->has('start_date'))) {
            $journals = Journal::paginate($limit);
        } else {
            $start_date = request('start_date') . ' 00:00:00';
            $end_date = request('end_date') . ' 23:59:59';

            $journals = Journal::whereBetween('created_at', [$start_date, $end_date])
                ->paginate($limit);
        }

        $start_date = request('start_date', Date::now()->startOfYear()->format('Y-m-d'));
        $end_date = request('end_date', Date::now()->endOfYear()->format('Y-m-d'));
        $date_format = $this->getCompanyDateFormat();

        return view('double-entry::journal_entry.index', compact('journals', 'start_date', 'end_date', 'date_format'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('journal-entry.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = [];

        $types = Type::pluck('name', 'id')->map(function ($name) {
            return trans($name);
        })->toArray();

        $all_accounts = Account::with(['type'])->enabled()->orderBy('code')->get();

        foreach ($all_accounts as $account) {
            if (!isset($types[$account->type_id])) {
                continue;
            }

            $accounts[$types[$account->type_id]][$account->id] = $account->code . ' - ' . trans($account->name);
        }

        ksort($accounts);

        $currency = Currency::where('code', '=', setting('default.currency', 'USD'))->first();

        $currency->precision = (int) $currency->precision;

        return view('double-entry::journal_entry.create', compact('accounts', 'currency'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->input();

        $amount = 0;

        $input['amount'] = $amount;

        $journal = Journal::create($input);

        foreach ($input['items'] as $item) {
            if (!empty($item['debit'])) {
                $journal->ledger()->create([
                    'company_id' => session('company_id'),
                    'account_id' => $item['account_id'],
                    'issued_at' => $journal->paid_at,
                    'entry_type' => 'item',
                    'debit' => $item['debit'],
                ]);

                $amount += $item['debit'];
            } else {
                $journal->ledger()->create([
                    'company_id' => session('company_id'),
                    'account_id' => $item['account_id'],
                    'issued_at' => $journal->paid_at,
                    'entry_type' => 'item',
                    'credit' => $item['credit'],
                ]);
            }
        }

        $journal->amount = $amount;

        $journal->save();

        $message = trans('messages.success.added', ['type' => trans('double-entry::general.journal_entry')]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => [],
            'redirect' => route('journal-entry.index'),
            'message' => $message,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Journal  $journal_entry
     *
     * @return Response
     */
    public function edit(Journal $journal_entry)
    {
        $journal = $journal_entry;

        $accounts = [];

        $types = Type::pluck('name', 'id')->map(function ($name) {
            return trans($name);
        })->toArray();

        $all_accounts = Account::with(['type'])->enabled()->orderBy('code')->get();

        foreach ($all_accounts as $account) {
            if (!isset($types[$account->type_id])) {
                continue;
            }

            $accounts[$types[$account->type_id]][$account->id] = $account->code . ' - ' . trans($account->name);
        }

        ksort($accounts);

        foreach ($journal->ledgers as $ledger) {
            if (!empty($ledger->debit)) {
                $journal->debit_account_id = $ledger->account_id;
                $journal->debit_amount = $ledger->debit;
            } else {
                $journal->credit_account_id = $ledger->account_id;
                $journal->credit_amount = $ledger->credit;
            }
        }

        $currency = Currency::where('code', '=', setting('default.currency', 'USD'))->first();

        $currency->precision = (int) $currency->precision;

        return view('double-entry::journal_entry.edit', compact('journal', 'accounts', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Journal  $journal_entry
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Journal $journal_entry, Request $request)
    {
        $input = $request->input();

        $amount = 0;

        $input['amount'] = $amount;

        $this->deleteRelationships($journal_entry, ['ledgers']);

        $journal_entry->update($input);

        foreach ($input['items'] as $item) {
            if (!empty($item['debit'])) {
                $journal_entry->ledger()->create([
                    'company_id' => session('company_id'),
                    'account_id' => $item['account_id'],
                    'issued_at' => $journal_entry->paid_at,
                    'entry_type' => 'item',
                    'debit' => $item['debit'],
                ]);

                $amount += $item['debit'];
            } else {
                $journal_entry->ledger()->create([
                    'company_id' => session('company_id'),
                    'account_id' => $item['account_id'],
                    'issued_at' => $journal_entry->paid_at,
                    'entry_type' => 'item',
                    'credit' => $item['credit'],
                ]);
            }
        }

        $journal_entry->amount = $amount;

        $journal_entry->save();
/*
        $ledger = Ledger::record($journal_entry->id, get_class($journal_entry))->whereNull('debit')->first();
        $ledger->account_id = $input['credit_account_id'];
        $ledger->issued_at = $journal_entry->paid_at;
        $ledger->credit = $input['credit_amount'];
        $ledger->save();

        $ledger = Ledger::record($journal_entry->id, get_class($journal_entry))->whereNull('credit')->first();
        $ledger->account_id = $input['debit_account_id'];
        $ledger->issued_at = $journal_entry->paid_at;
        $ledger->debit = $input['debit_amount'];
        $ledger->save();*/

        $message = trans('messages.success.updated', ['type' => trans('double-entry::general.journal_entry')]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => [],
            'redirect' => route('journal-entry.index'),
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Journal  $journal_entry
     *
     * @return Response
     */
    public function destroy(Journal $journal_entry)
    {
        $this->deleteRelationships($journal_entry, ['ledgers']);
        
        $journal_entry->delete();

        $message = trans('messages.success.deleted', ['type' => trans('double-entry::general.journal_entry')]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $message,
            'message' => '',
            'redirect' => route('journal-entry.index')
        ]);
    }

    public function addItem(ItemRequest $request)
    {
        if ($request['item_row']) {
            $accounts = [];

            $types = Type::pluck('name', 'id')->map(function ($name) {
                return trans($name);
            })->toArray();

            $all_accounts = Account::with(['type'])->enabled()->orderBy('code')->get();

            foreach ($all_accounts as $account) {
                if (!isset($types[$account->type_id])) {
                    continue;
                }

                $accounts[$types[$account->type_id]][$account->id] = $account->code . ' - ' . trans($account->name);
            }

            ksort($accounts);

            $item_row = $request['item_row'];

            $currency = Currency::where('code', '=', setting('default.currency', 'USD'))->first();

            // it should be integer for amount mask
            $currency->precision = (int) $currency->precision;

            $html = view('double-entry::journal-entry.item', compact('item_row', 'accounts', 'currency'))->render();

            return response()->json([
                'success' => true,
                'error'   => false,
                'data'    => [
                    'currency' => $currency,
                ],
                'message' => 'null',
                'html'    => $html,
            ]);
        }

        return response()->json([
            'success' => false,
            'error'   => true,
            'data'    => 'null',
            'message' => trans('issue'),
            'html'    => 'null',
        ]);
    }

    public function totalItem(ItemRequest $request)
    {
        $input_items = $request->input('item');
        $currency_code = $request->input('currency_code');

        if (empty($currency_code)) {
            $currency_code = setting('default.currency');
        }

        $json = new \stdClass;

        $debit_sub_total = 0;
        $credit_sub_total = 0;

        if ($input_items) {
            foreach ($input_items as $item) {
                $debit_sub_total += (double) $item['debit'];
                $credit_sub_total += (double) $item['credit'];
            }
        }

        $json->debit_sub_total = money($debit_sub_total, $currency_code, true)->format();
        $json->credit_sub_total = money($credit_sub_total, $currency_code, true)->format();

        $debit_grand_total = $debit_sub_total;
        $credit_grand_total = $credit_sub_total;

        if ($debit_grand_total > $credit_grand_total) {
            $credit_grand_total = $credit_grand_total - $debit_grand_total;
        } elseif ($debit_grand_total < $credit_grand_total) {
            $debit_grand_total = $debit_grand_total - $credit_grand_total;
        }

        $json->debit_grand_total = money($debit_grand_total, $currency_code, true)->format();
        $json->credit_grand_total = money($credit_grand_total, $currency_code, true)->format();

        $json->debit_grand_total_raw = $debit_sub_total;
        $json->credit_grand_total_raw = $credit_sub_total;

        return response()->json($json);
    }
}
