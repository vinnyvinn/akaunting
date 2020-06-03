<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Contact as Request;
use App\Jobs\Common\CreateContact;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Setting\Currency;
use Illuminate\Support\Facades\Auth;
use Modules\Projects\Http\Requests\ProjectRequest;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\ProjectUser;
use App\Traits\Contacts;
use App\Traits\NewRecordResponse;

class Projects extends Controller
{
    use Contacts, NewRecordResponse;
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:read-projects')->only(['create', 'store', 'duplicate', 'import']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $customers = Contact::type($this->getCustomerTypes())->pluck('name', 'id');
        $users = Company::find(session('company_id'))->users()->pluck('name', 'id');

        $html = view('modals.projects.create', compact('customers', 'users'))->render();

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
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(ProjectRequest $request)
    {
        $data = [
        'company_id' => session('company_id'),
        'name' => $request->name,
        'description' => $request->description,
        'customer_id' => $request->customer_id,
        'started_at' => $request->started_at,
        'ended_at' => $request->ended_at,
        'status' => 0
        ];
        $response =  $this->ajaxResponse(new Project(),$data);
        $members = request('members', array());

        if (!in_array(Auth::id(), $members)) {
            array_push($members, Auth::id());
        }

        foreach ($members as $member) {
            ProjectUser::create([
                'company_id' => session('company_id'),
                'project_id' => $response['data']->id,
                'user_id' => $member,
            ]);
        }

        $message = trans('projects::general.success');
        flash($message)->success();
        return response()->json($response);
    }
}
