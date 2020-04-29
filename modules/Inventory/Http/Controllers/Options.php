<?php

namespace Modules\Inventory\Http\Controllers;

use App\Abstracts\Http\Controller;

use Modules\Inventory\Models\Option;
use Modules\Inventory\Models\OptionValue;

use Modules\Inventory\Http\Requests\Option as Request;

class Options extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $options = Option::collect();

        return view('inventory::options.index', compact('options'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('options.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Responser
     */
    public function create()
    {
        $types = [
            trans('inventory::options.types.choose') => [
                'select' => trans('inventory::options.types.select'),
                'radio' => trans('inventory::options.types.radio'),
                'checkbox' => trans('inventory::options.types.checkbox')
            ],
            trans('inventory::options.types.input') => [
                'text' => trans('inventory::options.types.text'),
                'textarea' => trans('inventory::options.types.textarea')
            ],
        ];

        return view('inventory::options.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $option = Option::create($request->all());

        if ($option->type == 'select' || $option->type == 'radio' || $option->type == 'checkbox') {
            $option_items = $request->get('items');

            foreach ($option_items as $option_item) {
                if (empty($option_item['name'])) {
                    continue;
                }

                OptionValue::create([
                    'company_id' => $option->company_id,
                    'option_id' => $option->id,
                    'name' => $option_item['name']
                ]);
            }
        }

        $message = trans('messages.success.updated', ['type' => $option->name]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => [],
            'redirect' => route('options.index'),
            'message' => $message
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Option $option
     *
     * @return Response
     */
    public function edit(Option $option)
    {
        $types = [
            trans('inventory::options.types.choose') => [
                'select' => trans('inventory::options.types.select'),
                'radio' => trans('inventory::options.types.radio'),
                'checkbox' => trans('inventory::options.types.checkbox')
            ],
            trans('inventory::options.types.input') => [
                'text' => trans('inventory::options.types.text'),
                'textarea' => trans('inventory::options.types.textarea')
            ],
        ];

        return view('inventory::options.edit', compact('option', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Option $option
     * @param  Request      $request
     *
     * @return Response
     */
    public function update(Option $option, Request $request)
    {
        $relationships = [];

        if (empty($relationships) || $request['enabled']) {
            $option->update($request->all());

            $values = OptionValue::where('option_id', $option->id)->get();

            if ($values) {
                foreach ($values as $value) {
                    $value->delete();
                }
            }

            if ($option->type == 'select' || $option->type == 'radio' || $option->type == 'checkbox') {
                $option_items = $request->get('items');

                foreach ($option_items as $option_item) {
                    if (empty($option_item['name'])) {
                        continue;
                    }

                    OptionValue::create([
                        'company_id' => $option->company_id,
                        'option_id' => $option->id,
                        'name' => $option_item['name']
                    ]);
                }
            }
        }

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('options.index'),
            'data' => [],
        ];

        $message = trans('messages.success.updated', ['type' => trans_choice('inventory::general.options', 1)]);

        flash($message)->success();

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Option $option
     *
     * @return Response
     */
    public function enable(Option $option)
    {
        $option->enabled = 1;
        $option->save();

        $response = [
            'success' => true,
            'error' => false,
            'data' => $option,
            'message' => '',
        ];

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $option->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Option $option
     *
     * @return Response
     */
    public function disable(Option $option)
    {
        $relationships = [];

        if (empty($relationships)) {
            $option->enabled = 0;
            $option->save();

            $response = [
                'success' => true,
                'error' => false,
                'data' => $option,
                'message' => '',
            ];

            if ($response['success']) {
                $response['message'] = trans('messages.success.disabled', ['type' => $option->name]);
            }

            return response()->json($response);
        } else {
            $response = [
                'success' => false,
                'error' => false,
                'data' => $option,
                'message' => '',
            ];

            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Option $option
     *
     * @return Response
     */
    public function destroy(Option $option)
    {
        $option->delete();

        $response = [
        'success' => true,
        'error' => false,
        'redirect' => route('options.index'),
        'data' => [],
         ];

        $message = $response['message'] = trans('messages.success.deleted', ['type' => $option->name]);

        flash($message)->success();

        return response()->json($response);
    }

}
