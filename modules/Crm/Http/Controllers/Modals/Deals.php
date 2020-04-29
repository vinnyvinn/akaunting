<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use App\Models\Setting\Currency;
use Modules\Crm\Jobs\CreateDeal;
use Modules\Crm\Jobs\DeleteDeal;
use Modules\Crm\Jobs\UpdateDeal;
use Modules\Crm\Models\Contact;
use Modules\Crm\Models\Company;
use Modules\Crm\Models\Deal;
use Modules\Crm\Models\DealPipeline;

use Modules\Crm\Http\Requests\Deal as Request;

class Deals extends Controller
{

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-crm-deals')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-crm-deals')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-crm-deals')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-crm-deals')->only('destroy');
    }

    public function index()
    {
        $response = [
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => '',
            'title' => 'Show Logs',
            'html' => 'Test Logssss'
        ];

        return response()->json($response);
    }

    public function show($type, $type_id, $id)
    {
        $log = Deal::find($id);

        $html = view('crm::modals.logs.show', compact('log'))->render();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $log,
            'message' => 'Result log details',
            'title' => trans('crm::general.modal.title', ['field' => trans_choice('crm::general.logs', 1)]),
            'html' => $html
        ];

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $contacts = Contact::with('contact')->enabled()->get()->pluck('name', 'id');

        $companies = Company::with('contact')->enabled()->get()->pluck('name', 'id');

        $pipelines = DealPipeline::get()->pluck('name', 'id');

        $owners = \App\Models\Common\Company::find(session('company_id'))->users()->pluck('name', 'id');

        $currency = Currency::where('code', setting('default.currency'))->first();

        $html = view('crm::modals.deals.create', compact('contacts', 'companies', 'owners', 'pipelines', 'currency'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request['created_by'] = user()->id;
        $request['company_id'] = session('company_id');

        $response = $this->ajaxDispatch(new CreateDeal($request));

        $response['redirect'] = route('crm.deals.index');

        if ($response['success']) {

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.deals', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Deal $deal
     *
     * @return Response
     */
    public function edit(Deal $deal)
    {
        $contacts = Contact::with('contact')->enabled()->get()->pluck('name', 'id');

        $companies = Company::with('contact')->enabled()->get()->pluck('name', 'id');

        $pipelines = DealPipeline::get()->pluck('name', 'id');

        $owners = \App\Models\Common\Company::find(session('company_id'))->users()->pluck('name', 'id');

        $currency = Currency::where('code', setting('default.currency'))->first();

        $html = view('crm::modals.deals.edit', compact('deal', 'contacts', 'companies', 'owners', 'pipelines', 'currency'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.deals', 1)]),
            'message' => 'Deal edit page',
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Deal $deal
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Deal $deal, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, $request));

        $response['redirect'] = route('crm.deals.index');

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.deals', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Deal $deal
     *
     * @return Response
     */
    public function destroy(Deal $deal)
    {
        $response = $this->ajaxDispatch(new DeleteDeal($deal));

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.deals', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }

    public function ownerEdit(Deal $deal, $owner_id)
    {
        $company_id = session('company_id');

        $owners = \App\Models\Common\Company::find($company_id)->users()->pluck('name', 'id');

        $html = view('crm::partials.modals.deals.owner_edit', compact('deal', 'owners'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function ownerUpdate(Deal $deal, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['owner_id' => $request->owner_id])));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans_choice('crm::general.deals', 1)]);
        }

        return response()->json($response);
    }

    public function companyEdit(Deal $deal, $deal_company_id)
    {
        $company_id = session('company_id');

        $companies = Company::with('contact')->where('company_id', session('company_id'))->get()->pluck('contact.name', 'id');

        $html = view('crm::partials.modals.deals.company_edit', compact('deal', 'companies'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function companyUpdate(Deal $deal, $crm_company_id, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['crm_company_id' => $request->crm_company_id])));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {
            $response['message'] = $message = trans('messages.success.enabled', ['type' => trans_choice('crm::general.deals', 1)]);

            flash($message)->success();
        }

        return response()->json($response);
    }

    public function contactEdit(Deal $deal, $deal_contact_id)
    {
        $company_id = session('company_id');

        $contacts = Contact::with('contact')->where('company_id', session('company_id'))->get()->pluck('contact.name', 'id');

        $html = view('crm::partials.modals.deals.contact_edit', compact('deal', 'contacts'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function contactUpdate(Deal $deal, $crm_contact_id, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDeal($deal, request()->merge(['crm_contact_id' => $request->crm_contact_id])));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {
            $response['message'] = $message = trans('messages.success.enabled', ['type' => trans_choice('crm::general.deals', 1)]);

            flash($message)->success();
        }

        return response()->json($response);
    }
}
