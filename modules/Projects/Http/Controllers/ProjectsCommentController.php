<?php

namespace Modules\Projects\Http\Controllers;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Projects\Models\Comment;
use Modules\Projects\Models\Discussion;

class ProjectsCommentController extends Controller
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
        $comment = new Comment();
        $comment->company_id = session('company_id');
        $comment->project_id = $request->project_id;
        $comment->discussion_id = $request->discussion_id;
        $comment->comment = $request->comment;
        $comment->created_by = Auth::id();
        
        $comment->save();
        
        $discussion = Discussion::where('id', $request->discussion_id)->first();
        $discussion->total_comment += 1;
        $discussion->save();
        
        $message = '';
        
        $user = User::where('id', $comment->created_by)->first();
        $comment->created_by = $user->name;
        
        if ($user->picture) {
            if (setting('default.use_gravatar', '0') == '1') {
                $comment['user_image_path'] = $user->picture;
            } else {
                $comment['user_image_path'] = Storage::url($user->picture->id);
            }
        } else {
            $comment['user_image_path'] = asset('public/img/user.png');
        }
        
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $comment,
            'message' => $message,
            'totalComment' => $discussion->total_comment
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
    public function destroy()
    {
    }
}
