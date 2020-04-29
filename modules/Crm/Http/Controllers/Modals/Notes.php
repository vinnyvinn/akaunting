<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use Modules\Crm\Jobs\CreateNote;
use Modules\Crm\Jobs\DeleteNote;
use Modules\Crm\Jobs\UpdateNote;
use Modules\Crm\Models\Note;

use Illuminate\Http\Request as Request;

class Notes extends Controller
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
            'title' => 'Show Notes',
            'html' => 'Test Notesssssss'
        ];

        return response()->json($response);
    }

    public function show($type, $type_id, $id)
    {
        $note = Note::find($id);

        $html = view('crm::modals.notes.show', compact('note'))->render();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $note,
            'message' => 'Result note details',
            'title' => trans('crm::general.modal.title', ['field' => trans_choice('crm::general.notes', 1)]),
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

        $response = $this->ajaxDispatch(new CreateNote($request));

        $route = 'crm.' . $type . '.show';

        if ($response['success']) {
            $response['redirect'] = route($route, $type_id);

            $message = trans('messages.success.added', ['type' => trans_choice('crm::general.notes', 1)]);

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
     * @param  Note $note
     *
     * @return Response
     */
    public function edit($type, $type_id, Note $note)
    {
        $html = view('crm::modals.notes.edit', compact('note', 'type', 'type_id'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'title' => trans('crm::general.modal.edit.title', ['field' => trans_choice('crm::general.notes', 1)]),
            'message' => 'Note edit page',
            'html' => $html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Note $note
     * @param  Request $request
     *
     * @return Response
     */
    public function update($type, $type_id, Note $note, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateNote($note, $request));

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans_choice('crm::general.notes', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Note $note
     *
     * @return Response
     */
    public function destroy($type, $type_id, Note $note)
    {
        $response = $this->ajaxDispatch(new DeleteNote($note));

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.notes', 1)]);

            $response['message'] = $message;
        }

        return response()->json($response);
    }
}
