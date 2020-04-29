<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use Modules\Crm\Jobs\CreateDealCompetitor;
use Modules\Crm\Jobs\DeleteDealCompetitor;
use Modules\Crm\Jobs\UpdateDealCompetitor;
use Modules\Crm\Models\Deal;
use Modules\Crm\Models\DealCompetitor;

use Modules\Crm\Http\Requests\DealCompetitor as Request;

class DealCompetitors extends Controller
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
    public function create(Deal $deal, Request $request)
    {
        $html = view('crm::modals.deal-competitors.create', compact('deal'))->render();

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
     * @param  Deal $deal
     * @param  $request
     * @return Response
     */
    public function store(Deal $deal, Request $request)
    {
        $request['company_id'] = session('company_id');
        $request['created_by'] = user()->id;
        $request['deal_id'] = $deal->id;

        $response = $this->ajaxDispatch(new CreateDealCompetitor($request->input()));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.competitors', 1)]);

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
    public function edit(Deal $deal, $deal_competitor_id)
    {
        $deal_competitor = DealCompetitor::find($deal_competitor_id);

        $html = view('crm::modals.deal-competitors.edit', compact('deal', 'deal_competitor'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.competitors', 1)]),
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
    public function update(Deal $deal, $deal_competitor_id, Request $request)
    {
        $deal_competitor = DealCompetitor::find($deal_competitor_id);

        $request['company_id'] = session('company_id');
        $request['created_by'] = user()->id;
        $request['deal_id'] = $deal->id;

        $response = $this->ajaxDispatch(new UpdateDealCompetitor($deal_competitor, $request->input()));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.competitors', 1)]);

            $response['message'] = $message;

            flash($message)->success();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Deal $deal
     * @param  DealCompetitor $deal_competitor
     *
     * @return Response
     */
    public function destroy(Deal $deal, $deal_competitor_id)
    {
        $deal_competitor = DealCompetitor::find($deal_competitor_id);

        $response = $this->ajaxDispatch(new DeleteDealCompetitor($deal_competitor));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.competitors', 1)]);

            $response['message'] = $message;

            flash($message)->success();
        }

        return response()->json($response);
    }
}
