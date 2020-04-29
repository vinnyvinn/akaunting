<?php
namespace Modules\Projects\Http\Controllers;

use App\Models\Common\Company;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Projects\Models\SubTask;
use Modules\Projects\Models\ProjectSubtaskUser;

class ProjectsSubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('projects::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('projects::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $subtask = new SubTask();
        $subtask->company_id = session('company_id');
        $subtask->project_id = $request->project_id;
        $subtask->task_id = $request->task_id;
        $subtask->name = $request->name;
        $subtask->description = $request->description;
        $subtask->created_by = Auth::id();
        $subtask->deadline_at = $request->deadline_at;
        $subtask->priority = $request->priority;
        $subtask->status = $request->status;
        
        if (SubTask::where('task_id', $request->task_id)->count() > 0) {
            $subtask->order_number = SubTask::where('task_id', $request->task_id)->max('order_number') + 1;
        }
        
        $subtask ->save();
        
        $members = request('members', array());
        
        if (!in_array(Auth::id(), $members)) {
            array_push($members, Auth::id());
        }
        
        foreach ($members as $member) {
            ProjectSubtaskUser::create([
                'company_id' => session('company_id'),
                'project_id' => request('project_id'),
                'task_id' => request('task_id'),
                'subtask_id' => $subtask->id,
                'user_id' => $member
            ]);
        }
        
        $message = trans('projects::messages.success.added', [
            'type' => trans_choice('projects::general.subtask', 1)
        ]);

        $taskPriorities = $this->getPriorityList();
        $taskStatusList = $this->getTaskStatusList();

        foreach ($taskPriorities as $key => $value) {
            if ($key == $subtask->priority) {
                $subtask['priority_text'] = $value;
            }
        }

        foreach ($taskStatusList as $key => $value) {
            if ($key == $subtask->status) {
                $subtask['status_text'] = $value;
            }
        }

        foreach ($subtask->users as $user) {
            $user['username'] = User::where('id', $user->user_id)->first()->name;

            if (User::where('id', $user->user_id)->first()->picture) {
                if (setting('general.use_gravatar', '0') == '1') {
                    $user['picture'] = '<img src="' + User::where('id', $user->user_id)->first()->picture + '" class="img-circle img-sm" alt="User Image">';
                }
                else{ 
                    $user['picture'] = '<img src="' + Storage::url(User::where('id', $user->user_id)->first()->picture->id) + '" class="img-circle img-sm" alt="User Image">';
                } 
            }
            else{
                $user['picture'] = '<i class="fas fa-user"></i>';
            }  
        }

        $subtask['users'] = $subtask->users;
        
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $subtask,
            'message' => $message
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('projects::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('projects::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(SubTask $subtask, Request $request)
    {
        $subtask->name = $request->name;
        $subtask->description = $request->description;
        $subtask->deadline_at = $request->deadline_at;
        $subtask->priority = $request->priority;
        $subtask->status = $request->status;
        $subtask->update();
        
        $allMembers = ProjectSubtaskUser::where(['company_id' => session('company_id'), 'subtask_id' => $subtask->id])->get()->pluck('user_id')->toArray();
        $members = request('members');

        if (!in_array(Auth::id(), $members)) {
            array_push($members, Auth::id());
        }

        foreach ($allMembers as $member) {
            if (! in_array($member, $members)) {
                ProjectSubtaskUser::where(['company_id' => session('company_id'), 'subtask_id' => $subtask->id, 'user_id' => $member])->delete();
            }
        }
        
        foreach ($members as $newMember) {
            if (! in_array($newMember, $allMembers)) {
                ProjectSubtaskUser::create(['company_id' => session('company_id'), 'project_id' => $subtask->project_id, 'task_id' => $subtask->task_id, 'subtask_id' => $subtask->id, 'user_id' => $newMember]);
            }
        }
        
        $message = trans('projects::messages.success.updated', [
            'type' => trans_choice('projects::general.subtask', 1)
        ]);

        $taskPriorities = $this->getPriorityList();
        $taskStatusList = $this->getTaskStatusList();

        foreach ($taskPriorities as $key => $value) {
            if ($key == $subtask->priority) {
                $subtask['priority_text'] = $value;
            }
        }

        foreach ($taskStatusList as $key => $value) {
            if ($key == $subtask->status) {
                $subtask['status_text'] = $value;
            }
        }

        foreach ($subtask->users as $user) {
            $user['username'] = User::where('id', $user->user_id)->first()->name;

            if (User::where('id', $user->user_id)->first()->picture) {
                if (setting('general.use_gravatar', '0') == '1') {
                    $user['picture'] = '<img src="' + User::where('id', $user->user_id)->first()->picture + '" class="img-circle img-sm" alt="User Image">';
                }
                else{ 
                    $user['picture'] = '<img src="' + Storage::url(User::where('id', $user->user_id)->first()->picture->id) + '" class="img-circle img-sm" alt="User Image">';
                } 
            }
            else{
                $user['picture'] = '<i class="fas fa-user"></i>';
            }  
        }

        $subtask['users'] = $subtask->users;
        
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $subtask,
            'message' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(SubTask $subtask)
    {
        $subtask->delete();
        $subtask->users()->delete();
        
        $message = trans('projects::messages.success.deleted', [
            'type' => trans_choice('projects::general.subtask', 1)
        ]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $subtask,
            'message' => $message,
            'html' => 'null'
        ]);
    }

    public function adjustOrder(Request $request){
        foreach ($request->all() as $key => $value){
            $subtask = SubTask::find(explode('-', $key)[1]);
            $subtask->order_number = $value;
            $subtask->update();
        }
        
        return response()->json(['status' => 'OK']);
    }

    public function makeComplete(Subtask $subtask){
        $subtask->order_number = 999999;
        $subtask->status = 2;
        $subtask->update();
        
        $allSubtasks = SubTask::where(['task_id' => $subtask->task_id, 'company_id' => session('company_id')])->where('order_number', '!=', '999999')->get();
        $counter = 0;
        
        foreach ($allSubtasks as $tempSubtask) {
            $tempSubtask->order_number = $counter; 
            $tempSubtask->update();
            $counter += 1;
        }
        
        return response()->json(['status' => 'OK']);
    }

    public function makeNotStarted(Subtask $subtask){
        $allSubtasks = SubTask::where(['task_id' => $subtask->task_id, 'company_id' => session('company_id')])->where('order_number', '!=', '999999')->get()->sortBy('order_number');
        $counter = 1;
        
        foreach ($allSubtasks as $tempSubtask) {
            $tempSubtask->order_number = $counter; 
            $tempSubtask->update();
            $counter += 1;
        }
        
        $subtask->order_number = 0;
        $subtask->status = 0;
        $subtask->update();
        
        return response()->json(['status' => 'OK']);
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
