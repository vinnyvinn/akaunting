<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use App\Models\Common\Company;
use Modules\Crm\Jobs\CreateTask;
use Modules\Crm\Jobs\DeleteTask;
use Modules\Crm\Jobs\UpdateTask;
use Modules\Crm\Models\Task;

use Illuminate\Http\Request as Request;

class Tasks extends Controller
{
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
            'title' => 'Show Tasks',
            'html' => 'Test Tasks'
        ];

        return response()->json($response);
    }

    public function show($type, $type_id, $id)
    {
        $task = Task::find($id);

        $html = view('crm::modals.tasks.show', compact('task'))->render();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $task,
            'message' => 'Result task details',
            'title' => trans('crm::general.modal.title', ['field' => trans_choice('crm::general.tasks', 1)]),
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

        $response = $this->ajaxDispatch(new CreateTask($request));

        $route = 'crm.' . $type . '.show';

        if ($response['success']) {
            $response['redirect'] = route($route, $type_id);

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.tasks', 1)]);

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
     * @param  Task $task
     *
     * @return Response
     */
    public function edit($type, $type_id, Task $task)
    {
        $company_id = session('company_id');

        $users = Company::find($company_id)->users()->pluck('name', 'id');

        $html = view('crm::modals.tasks.edit', compact('task', 'type', 'type_id', 'users'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.tasks', 1)]),
            'message' => 'Task edit page',
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Task $task
     * @param  Request $request
     *
     * @return Response
     */
    public function update($type, $type_id, Task $task, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTask($task, $request));

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.tasks', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task $task
     *
     * @return Response
     */
    public function destroy($type, $type_id, Task $task)
    {
        $response = $this->ajaxDispatch(new DeleteTask($task));

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.tasks', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }
}
