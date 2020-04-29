<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use App\Models\Setting\Currency;
use Modules\Crm\Jobs\CreateDealAgent;
use Modules\Crm\Jobs\DeleteDealAgent;
use Modules\Crm\Jobs\UpdateDealAgent;
use Modules\Crm\Models\Deal;
use Modules\Crm\Models\DealAgent;

use Modules\Crm\Http\Requests\DealAgent as Request;

class DealAgents extends Controller
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
        if (!empty($deal->agents->count())) {
            $data = [];

            foreach ($deal->agents as $agent) {
                $data[] = $agent->user_id;
            }

            $assigneds = \App\Models\Common\Company::find(session('company_id'))->users()->whereNotIn('id', $data)->pluck('name', 'id');
        } else {
            $assigneds = \App\Models\Common\Company::find(session('company_id'))->users()->pluck('name', 'id');
        }

        $html = view('crm::modals.deal-agents.create', compact('deal', 'assigneds'))->render();

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

        $response = $this->ajaxDispatch(new CreateDealAgent($request->input()));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.agents', 1)]);

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
    public function edit(Deal $deal, $deal_agent_id)
    {
        $deal_agent = DealAgent::find($deal_agent_id);

        if (!empty($deal->agents->count())) {
            $data = [];

            foreach ($deal->agents as $agent) {
                $data[] = $agent->user_id;
            }

            $assigneds = \App\Models\Common\Company::find(session('company_id'))->users()->whereNotIn('id', $data)->pluck('name', 'id');
        } else {
            $assigneds = \App\Models\Common\Company::find(session('company_id'))->users()->pluck('name', 'id');
        }

        $html = view('crm::modals.deal-agents.edit', compact('deal', 'deal_agent', 'assigneds'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.agents', 1)]),
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
    public function update(Deal $deal, $deal_agent_id, Request $request)
    {
        $deal_agent = DealAgent::find($deal_agent_id);

        $request['company_id'] = session('company_id');
        $request['created_by'] = user()->id;
        $request['deal_id'] = $deal->id;

        $response = $this->ajaxDispatch(new UpdateDealAgent($deal_agent, $request));

        $response['redirect'] = route('crm.deals.index');

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.agents', 1)]);

            $response['message'] = $message;

            flash($message)->success();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Deal $deal
     * @param  DealAgent $deal_agent
     *
     * @return Response
     */
    public function destroy(Deal $deal, $deal_agent_id)
    {
        $deal_agent = DealAgent::find($deal_agent_id);

        $response = $this->ajaxDispatch(new DeleteDealAgent($deal_agent));

        $response['redirect'] = route('crm.deals.show', $deal->id);

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.agents', 1)]);

            $response['message'] = $message;

            flash($message)->success();
        }

        return response()->json($response);
    }
}
