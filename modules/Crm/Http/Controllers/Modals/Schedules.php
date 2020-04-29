<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use App\Models\Common\Company;
use Modules\Crm\Jobs\CreateSchedule;
use Modules\Crm\Jobs\DeleteSchedule;
use Modules\Crm\Jobs\UpdateSchedule;
use Modules\Crm\Models\Schedule;
use Modules\Crm\Traits\Main;

use Illuminate\Http\Request as Request;

class Schedules extends Controller
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
            'title' => '',
            'html' => ''
        ];

        return response()->json($response);
    }

    public function show($type, $type_id, $id)
    {
        $schedule = Schedule::find($id);

        $html = view('crm::modals.schedules.show', compact('schedule'))->render();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $schedule,
            'message' => 'Result schedule details',
            'title' => trans('crm::general.modal.title', ['field' => trans_choice('crm::general.schedules', 1)]),
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

        $response = $this->ajaxDispatch(new CreateSchedule($request));

        $route = 'crm.' . $type . '.show';

        if ($response['success']) {
            $response['redirect'] = route($route, $type_id);

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.schedules', 1)]);

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
     * @param  Schedule $schedule
     *
     * @return Response
     */
    public function edit($type, $type_id, Schedule $schedule)
    {
        $types = $this->getTypes('schedules');

        $company_id = session('company_id');

        $users = Company::find($company_id)->users()->pluck('name', 'id');

        $html = view('crm::modals.schedules.edit', compact('schedule', 'type', 'type_id', 'types', 'users'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.schedules', 1)]),
            'message' => 'Schedule edit page',
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Schedule $schedule
     * @param  Request $request
     *
     * @return Response
     */
    public function update($type, $type_id, Schedule $schedule, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateSchedule($schedule, $request));

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.schedules', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Schedule $schedule
     *
     * @return Response
     */
    public function destroy($type, $type_id, Schedule $schedule)
    {
        $response = $this->ajaxDispatch(new DeleteSchedule($schedule));

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.schedules', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }
}
