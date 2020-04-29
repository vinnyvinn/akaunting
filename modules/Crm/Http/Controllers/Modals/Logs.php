<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use Modules\Crm\Jobs\CreateLog;
use Modules\Crm\Jobs\DeleteLog;
use Modules\Crm\Jobs\UpdateLog;
use Modules\Crm\Models\Log;
use Modules\Crm\Traits\Main;

use Illuminate\Http\Request as Request;

class Logs extends Controller
{
    use Main;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-settings-categories')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-settings-categories')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-settings-categories')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-settings-categories')->only('destroy');
    }

    public function index($type, $type_id, $id)
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
        $log = Log::find($id);

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
    public function create(Request $request)
    {
        $type = $request->get('type', 'item');

        $html = view('modals.categories.create', compact('type'))->render();

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
    public function store($type, $type_id, Request $request)
    {
        $request['id'] = $type_id;
        $request['type'] = $type;
        $request['created_by'] = user()->id;
        $request['company_id'] = session('company_id');
        $request['subject'] = 'null';

        $response = $this->ajaxDispatch(new CreateLog($request));

        $route = 'crm.' . $type . '.show';

        if ($response['success']) {
            $response['redirect'] = route($route, $type_id);

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.logs', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route($route, $type_id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Log $log
     *
     * @return Response
     */
    public function edit($type, $type_id, Log $log)
    {
        $types = $this->getTypes('logs');

        $html = view('crm::modals.logs.edit', compact('log', 'type', 'type_id', 'types'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.logs', 1)]),
            'message' => 'Log edit page',
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Log $log
     * @param  Request $request
     *
     * @return Response
     */
    public function update($type, $type_id, Log $log, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateLog($log, $request));

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.logs', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Log $log
     *
     * @return Response
     */
    public function destroy($type, $type_id, Log $log)
    {
        $response = $this->ajaxDispatch(new DeleteLog($log));

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.logs', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }
}
