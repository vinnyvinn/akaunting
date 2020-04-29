<?php

namespace Modules\Crm\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Traits\DateTime;
use Modules\Crm\Exports\Companies\Companies as Export;
use Modules\Crm\Http\Requests\Company as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use Modules\Crm\Imports\Companies\Companies as Import;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\UpdateContact;
use Modules\Crm\Jobs\CreateCompany as CreateCrmCompany;
use Modules\Crm\Jobs\DeleteCompany as DeleteCrmCompany;
use Modules\Crm\Jobs\UpdateCompany as UpdateCrmCompany;
use Modules\Crm\Models\Company as Model;
use App\Models\Common\Company;

use Modules\Crm\Traits\Activities;
use Modules\Crm\Traits\Contacts as ContactsTrait;
use Modules\Crm\Traits\Main;

class Companies extends Controller
{
    use Activities, ContactsTrait, DateTime, Main;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $companies = Model::collect();

        return view('crm::companies.index', compact('companies'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Model  $company
     *
     * @return Response
     */
    public function show(Model $company)
    {
        $activities = $this->getActivitiesByClass($company);

        $schedule_types = $this->getTypes('schedules');

        $log_types = $this->getTypes('logs');

        $user = user();

        $users = Company::find(session('company_id'))->users()->pluck('name', 'id');

        return view('crm::companies.show', compact('company', 'activities', 'schedule_types', 'log_types', 'users', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $company_id = session('company_id');

        $users = Company::find($company_id)->users()->pluck('name', 'id');

        $stages = $this->getStages();

        $sources = $this->getSources();

        return view('crm::companies.create', compact('users', 'stages', 'sources'));
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
        $response = $this->ajaxDispatch(new CreateContact($request));

        if ($response['success']) {
            $company = $response['data'];

            $request['contact_id'] = $company->id;
            $request['created_by'] = user()->id;

            $crm_company = $this->dispatch(new CreateCrmCompany($request));

            $response['redirect'] = route('crm.companies.index');

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.companies', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('crm.companies.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Model  $company
     *
     * @return Response
     */
    public function duplicate(Model $company)
    {
        $clone = $company->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('crm::general.companies', 1)]);

        flash($message)->success();

        return redirect()->route('crm.companies.edit', $clone->id);
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

        $message = trans('messages.success.imported', ['type' => trans_choice('crm::general.companies', 2)]);

        flash($message)->success();

        return redirect()->route('crm.companies.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Model  $company
     *
     * @return Response
     */
    public function edit(Model $company)
    {
        $company_id = session('company_id');

        $users = Company::find($company_id)->users()->pluck('name', 'id');

        $stages = $this->getStages();

        $sources = $this->getSources();

        return view('crm::companies.edit', compact('company', 'users', 'stages', 'sources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Model $company
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Model $company, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateContact($company->contact, $request));

        if ($response['success']) {
            $request['contact_id'] = $company->contact->id;
            $request['created_by'] = user()->id;

            $crm_company = $this->dispatch(new UpdateCrmCompany($company, $request));

            $response['redirect'] = route('crm.companies.index');

            $message = trans('messages.success.updated', ['type' => $company->contact->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('crm.companies.edit', $company->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Model $company
     *
     * @return Response
     */
    public function enable(Model $company)
    {
        $response = $this->ajaxDispatch(new UpdateContact($company->contact, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $company->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Model $company
     *
     * @return Response
     */
    public function disable(Model $company)
    {
        $response = $this->ajaxDispatch(new UpdateContact($company->contact, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $company->name]);
        }

        return response()->json($response);
    }

    public function destroy(Model $company)
    {
        $company_name = $company->contact->name;

        $response = $this->ajaxDispatch(new DeleteContact($company->contact));

        $crm_response = $this->dispatch(new DeleteCrmCompany($company));

        $response['redirect'] = route('customers.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $company_name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return \Excel::download(new Export(), trans_choice('crm::general.companies', 2) . '.xlsx');
    }
}
