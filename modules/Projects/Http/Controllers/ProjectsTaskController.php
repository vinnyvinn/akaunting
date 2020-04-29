<?php

namespace Modules\Projects\Http\Controllers;

use App\Models\Common\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Projects\Models\Task;
use Modules\Projects\Models\Project;

class ProjectsTaskController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('projects::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('projects::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $task = new Task();
        $task->company_id = session('company_id');
        $task->project_id = $request->project_id;
        $task->name = $request->name;
        $task->description = $request->description;

        $task->save();

        $message = trans('projects::messages.success.added', [
            'type' => trans_choice('projects::general.task', 1)
        ]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $task,
            'message' => $message
        ]);
    }

    /**
     * Show the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return view('projects::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        return view('projects::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Task $task, Request $request)
    {
        $task->name = $request->name;
        $task->description = $request->description;
        $task->update();

        $message = trans('projects::messages.success.updated', [
            'type' => trans_choice('projects::general.task', 1)
        ]);
        
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $task,
            'message' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Task $task, Request $request)
    {
        $task->subtasks()->delete();
        $task->delete();
        
        $message = trans('projects::messages.success.deleted', [
            'type' => trans_choice('projects::general.task', 1)
        ]);
        
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $task,
            'message' => $message,
            'html' => 'null'
        ]);
    }

    private function getPriorityList()
    {
        $priorities = [
            '0' => trans('projects::general.low'),
            '1' => trans('projects::general.medium'),
            '2' => trans('projects::general.high'),
            '3' => trans('projects::general.urgent')
        ];
        
        return $priorities;
    }
    
    private function getTaskStatusList()
    {
        $taskStatusList = [
            '0' => trans('projects::general.notstarted'),
            '1' => trans('projects::general.active'),
            '2' => trans('projects::general.completed')
        ];
        
        return $taskStatusList;
    }
}
