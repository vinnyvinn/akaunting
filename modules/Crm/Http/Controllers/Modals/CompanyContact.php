<?php

namespace Modules\Crm\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;

use Modules\Crm\Models\Company;
use Modules\Crm\Models\CompanyContact as CompanyContacts;
use Modules\Crm\Jobs\CreateCompanyContact;
use Modules\Crm\Jobs\DeleteCompanyContact;
use Modules\Crm\Models\Contact;


use Illuminate\Http\Request as Request;

class CompanyContact extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $field = 'Contact';

        $type_id = $company->id;

        $data = Contact::all()->pluck('name', 'id');

        $html = view('crm::modals.company.contact', compact('field', 'data', 'type_id'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Company $company)
    {
        $field = 'Contact';

        $type_id = $company->id;

        $data = Contact::all()->pluck('name', 'id');

        $html = view('crm::modals.company.contact', compact('company','field', 'data', 'type_id'))->render();

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
    public function store(Request $request)
    {
        $request['company_id'] = session('company_id');
        $request['created_by'] = user()->id;
        $request['crm_contact_id'] = $request->contact_type;
        $request['crm_company_id'] = $request->type_id;

        $response = $this->ajaxDispatch(new CreateCompanyContact($request->input()));

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/companies/'.$request->type_id),
            'data' => [],
        ];

        $message = trans('messages.success.added', ['type' => trans_choice('crm::general.contacts', 1)]);

        flash($message)->success();

        return response()->json($response);
    }

    public function destroy(Company $company, $company_contact_id)
    {
        $company_contact = CompanyContacts::find($company_contact_id);

        $response = $this->ajaxDispatch(new DeleteCompanyContact($company_contact));

        $response['redirect'] = route('crm.companies.show', $company->id);

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('crm::general.contact', 1)]);

            $response['message'] = $message;

            flash($message)->success();
        }

        return response()->json($response);
    }
}
