<?php
namespace Modules\Projects\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Projects\Models\Discussion;
use Modules\Projects\Models\DiscussionLike;
use Modules\Projects\Models\Project;

class ProjectsDiscussionLikeController extends Controller
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
        $discussion_like = new DiscussionLike();
        $discussion_like->company_id = session('company_id');
        $discussion_like->project_id = $request->project_id;
        $discussion_like->discussion_id = $request->discussion_id;
        $discussion_like->created_by = Auth::id();
        
        $discussion_like->save();
        
        $discussion = Discussion::where('id', $request->discussion_id)->first();
        $discussion->total_like += 1;
        $discussion->save();
        
        $project = Project::where('id', $request->project_id)->first();
        
        $message = '';
        $view = view('projects::modals.discussions.like', compact('discussion', 'project'))->render();
        
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $discussion_like,
            'message' => $message,
            'totalLike' => $discussion->total_like,
            'html' => $view
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
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $request)
    {
        $discussion_like = DiscussionLike::where(['discussion_id' => request()->segment(3), 'created_by' => Auth::id()])->first();
        $discussion_like->delete();
        
        $discussion = Discussion::where('id', request()->segment(3))->first();
        $discussion->total_like -= 1;
        $discussion->save();
        
//         $project = Project::where('id', $request->project_id)->first();
        $message = '';
//         $view = view('projects::modals.discussions.like', compact('discussion', 'project'))->render();
        
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $discussion_like,
            'message' => $message,
            'totalLike' => $discussion->total_like
        ]);
    }
    

}
