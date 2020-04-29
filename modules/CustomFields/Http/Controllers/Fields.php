<?php

namespace Modules\CustomFields\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\CustomFields\Http\Requests\Field as FRequest;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldLocation;
use Modules\CustomFields\Models\FieldTypeOption;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Models\Location;
use Modules\CustomFields\Models\Type;
use Modules\CustomFields\Traits\LocationSortOrder;

class Fields extends Controller
{
    use LocationSortOrder;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $custom_fields = Field::collect();

        return view('custom-fields::fields.index', compact('custom_fields'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $types = Type::pluck('name', 'id')->toArray();
        $locations = Location::pluck('name', 'id')->toArray();

        $orders = [
            'input_start' => trans('custom-fields::general.form.before'),
            'input_end' => trans('custom-fields::general.form.after'),
        ];

        $shows = [
            'always' => trans('custom-fields::general.form.shows.always'),
            'never' => trans('custom-fields::general.form.shows.never'),
            'if_filled' => trans('custom-fields::general.form.shows.if_filled')
        ];

        return view('custom-fields::fields.create', compact('types', 'locations', 'orders', 'shows'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  FRequest $request
     * @return Response
     */
    public function store(FRequest $request)
    {
        $request['locations'] = $request->location_id;

        $field = Field::create($request->input());

        $field_location = FieldLocation::create([
            'company_id' => session('company_id'),
            'field_id' => $field->id,
            'location_id' => $request->location_id,
            'sort_order' => $request->sort . '_' . $request->order,
        ]);

        $value = $request->value;
        $values = $request->items;

        if (!empty($value)) {
            $values[0]['values'] = $value;
        }

        if ($values) {
            foreach ($values as $value) {
                $field_type_option = FieldTypeOption::create([
                    'company_id' => session('company_id'),
                    'field_id' => $field->id,
                    'type_id' => $request->type_id,
                    'value' => $value['values'],
                ]);
            }
        } else {
            $field_type_option = FieldTypeOption::create([
                'company_id' => session('company_id'),
                'field_id' => $field->id,
                'type_id' => $request->type_id,
                'value' => '',
            ]);
        }

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('custom-fields.fields.index'),
            'data' => [],
        ];

        $message = trans('messages.success.added', ['type' => trans('custom-fields::general.name')]);

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('custom-fields::fields.show');
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Field  $field
     *
     * @return Response
     */
    public function duplicate(Field $field)
    {
        $clone = $field->duplicate();

        FieldLocation::create([
            'company_id' => session('company_id'),
            'field_id' => $clone->id,
            'location_id' => $clone->locations,
            'sort_order' => $field->fieldLocation->sort_order
        ]);

        $message = trans('messages.success.duplicated', ['type' => trans('custom-fields::general.name')]);

        flash($message)->success();

        return redirect('custom-fields/fields/' . $clone->id . '/edit');
    }

    /**
     * Show the form for editing the specified resource.
     * @param  Field $field
     * @return Response
     */
    public function edit(Field $field)
    {
        $types = Type::pluck('name', 'id');
        $locations = Location::pluck('name', 'id');

        $orders = [
            'input_start' => trans('custom-fields::general.form.before'),
            'input_end' => trans('custom-fields::general.form.after'),
        ];

        $shows = [
            'always' => trans('custom-fields::general.form.shows.always'),
            'never' => trans('custom-fields::general.form.shows.never'),
            'if_filled' => trans('custom-fields::general.form.shows.if_filled')
        ];

        $sort_values = $this->getLocationSortOrder($field->location->code);

        $field->location_id = $field->locations;

        $sort_order = explode('_input_', $field->fieldLocation->sort_order);

        $sort = $sort_order[0];
        $order = 'input_' . $sort_order[1];

        $field->sort = $sort;
        $field->order = $order;

        $view = 'type_option_value';

        if (($field->type->type == 'select') || ($field->type->type == 'radio') || ($field->type->type == 'checkbox')) {
            $view = 'type_option_values';
        }

        $custom_field_values = false;

        if ($field->fieldTypeOption) {
            $custom_field_values = $field->fieldTypeOption->pluck('value', 'id')->toArray();
        }

        // $html = view($view, compact('type', 'custom_field_values'))->render();

        // $field->type_option_html = $html;

        return view('custom-fields::fields.edit', compact('field', 'types', 'locations', 'orders', 'sort_values', 'shows', 'view', 'custom_field_values'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Field $field
     * @param  FRequest $request
     * @return Response
     */
    public function update(Field $field, FRequest $request)
    {
        $request['locations'] = $request->location_id;

        if ($field->locations != $request->location_id) {
            $this->deleteRelationships($field, 'fieldLocation');

            $field_location = FieldLocation::create([
                'company_id' => session('company_id'),
                'field_id' => $field->id,
                'location_id' => $request->location_id,
                'sort_order' => $request->sort . '_' . $request->order,
            ]);
        } else {
            $field_location = FieldLocation::where('field_id', '=', $field->id)->first();

            if ($field_location) {
                $field_location->sort_order = $request->sort . '_' . $request->order;
                $field_location->save();
            } else {
                $field_location = FieldLocation::create([
                    'company_id' => session('company_id'),
                    'field_id' => $field->id,
                    'location_id' => $request->location_id,
                    'sort_order' => $request->sort . '_' . $request->order,
                ]);
            }
        }

        $field->update($request->input());

        $this->deleteRelationships($field, 'fieldTypeOption');

        $value = $request->value;
        $values = $request->items;

        if (!empty($value)) {
            $values[0]['values'] = $value;
        }

        if ($values) {
            foreach ($values as $value) {
                $field_type_option = FieldTypeOption::create([
                    'company_id' => session('company_id'),
                    'field_id' => $field->id,
                    'type_id' => $request->type_id,
                    'value' => $value['values'],
                ]);
            }
        } else {
            $field_type_option = FieldTypeOption::create([
                'company_id' => session('company_id'),
                'field_id' => $field->id,
                'type_id' => $request->type_id,
                'value' => '',
            ]);
        }

        $response = [
            'success' => true,
            'error' => false,
            'data' => [],
            'message' => '',
        ];

        $response['redirect'] = route('custom-fields.fields.index');

        $message = trans('messages.success.updated', ['type' => trans('custom-fields::general.name')]);

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Field  $field
     *
     * @return Response
     */
    public function enable(Field $field)
    {
        $field->enabled = 1;
        $field->save();

        $response = [
            'success' => true,
            'error' => false,
            'data' => [],
            'message' => '',
        ];

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans('custom-fields::general.name')]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Field  $field
     *
     * @return Response
     */
    public function disable(Field $field)
    {
        $field->enabled = 0;
        $field->save();

        $response = [
            'success' => true,
            'error' => false,
            'data' => [],
            'message' => '',
        ];

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => trans('custom-fields::general.name')]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Field $field)
    {
        $this->deleteRelationships($field, ['fieldTypeOption']);
        //$this->deleteRelationships($field, ['fieldTypeOption', 'fieldLocation']);

        $field_location = FieldLocation::where('field_id', $field->id)->first();
        $field_location->delete();

        $field_values = FieldValue::where('field_id', $field->id)->get();

        foreach ($field_values as $field_value) {
            $field_value->delete();
        }

        $field->delete();

        $response = [
            'success' => true,
            'error' => false,
            'data' => [],
            'message' => '',
            'redirect' => route('custom-fields.fields.index')
        ];

        $message = trans('messages.success.deleted', ['type' => trans('custom-fields::general.name')]);

        flash($message)->success();

        return response()->json($response);
    }

    public function getType(Request $request)
    {
        $type = Type::firstWhere('id', $request['type_id']);

        $view = 'type_option_value';

        if ($type->type == 'select' || $type->type == 'radio' || $type->type == 'checkbox') {
            $view = 'type_option_values';
        }

        $custom_field_values = false;

        return response()->json([
            'type' => $type,
            'view' => $view
        ]);
    }

    public function getSortOrder(Request $request)
    {
        $location = Location::firstWhere('id', $request['location_id']);
        $sort_values = $this->getLocationSortOrder($location->code);

        $depend = $this->getLocationDepend($location->code);

        return response()->json([
            'data'    => [
                'type' => $location,
                'sort' => $sort_values,
                'depend' => $depend,
            ]
        ]);
    }

    public function getLocation(Request $request)
    {
        $location = Location::firstWhere('id', $request['location_id']);

        $html = view('custom-fields::locations.' . $location->code, compact('location'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'data'    => [
                'location' => $location
            ],
            'message' => 'null',
            'html'    => $html,
        ]);
    }
}
