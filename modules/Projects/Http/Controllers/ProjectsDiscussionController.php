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
use Modules\Projects\Models\DiscussionLike;
use Date;
use App\Traits\DateTime;

class ProjectsDiscussionController extends Controller
{
    use DateTime;

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
        $discussion = new Discussion();
        $discussion->company_id = session('company_id');
        $discussion->project_id = $request->project_id;
        $discussion->name = $request->name;
        $discussion->description = $request->description;
        $discussion->total_like = 0;
        $discussion->total_comment = 0;
        $discussion->created_by = Auth::id();

        $discussion->save();

        $message = trans('projects::messages.success.added', [
            'type' => trans_choice('projects::general.discussion', 1)
        ]);

        $discussion = array(
            'id' => $discussion->id,
            'name' => $discussion->name,
            'total_like' => $discussion->total_like,
            'total_comment' => $discussion->total_comment,
            'last_activity' => Date::parse($discussion->updated_at)->format($this->getCompanyDateFormat()),
            'updated_at' => $discussion->updated_at,
            'created_by' => User::where('id', $discussion->created_by)->first()->name
        );

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $discussion,
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
    public function update(Discussion $discussion, Request $request)
    {
        $discussion->name = $request->name;
        $discussion->description = $request->description;
        $discussion->update();

        $message = trans('projects::messages.success.updated', [
            'type' => trans_choice('projects::general.discussion', 1)
        ]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $discussion,
            'message' => $message,
            'html' => 'null'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Discussion $discussion, Request $request)
    {
        $discussion->comments()->delete();
        $discussion->delete();

        $message = trans('projects::messages.success.deleted', [
            'type' => trans_choice('projects::general.discussion', 1)
        ]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $discussion,
            'message' => $message,
            'html' => 'null'
        ]);
    }

    public function comments(Discussion $discussion, Request $request)
    {
        $comments = Comment::where('discussion_id', $discussion->id)->orderBy('created_at', 'asc')->get();

        foreach ($comments as $comment) {
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
        }

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $comments
        ]);
    }

    public function likes(Discussion $discussion, Request $request)
    {
        $likes = DiscussionLike::where('discussion_id', $discussion->id)->get();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $likes
        ]);
    }
}
