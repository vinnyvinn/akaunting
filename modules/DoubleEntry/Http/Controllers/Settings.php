<?php

namespace Modules\DoubleEntry\Http\Controllers;

use App\Abstracts\Http\Controller;
use Artisan;
use Illuminate\Http\Response;
use Modules\DoubleEntry\Http\Requests\Setting as Request;
use Modules\DoubleEntry\Models\Account;
use Modules\DoubleEntry\Models\DEClass;
use Modules\DoubleEntry\Models\Type;

class Settings extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
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

            $accounts[$types[$account->type_id]][$account->code] = $account->code . ' - ' . trans($account->name);
        }

        ksort($accounts);

        $types = [];

        $classes = DEClass::pluck('name', 'id')->map(function ($name) {
            return trans($name);
        })->toArray();

        $all_types = Type::all();

        foreach ($all_types as $type) {
            if (!isset($classes[$type->class_id])) {
                continue;
            }

            $types[$classes[$type->class_id]][$type->id] = trans($type->name);
        }

        ksort($types);

        return view('double-entry::settings.edit', compact('accounts', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        setting()->set('double-entry.accounts_receivable', $request['accounts_receivable']);
        setting()->set('double-entry.accounts_payable', $request['accounts_payable']);
        setting()->set('double-entry.accounts_sales', $request['accounts_sales']);
        setting()->set('double-entry.accounts_expenses', $request['accounts_expenses']);
        setting()->save();

        Artisan::call('cache:clear');

        $response = [
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => '',
        ];

        $response['redirect'] = route('double-entry.settings.edit');

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        flash($message)->success();

        return response()->json($response);
    }
}
