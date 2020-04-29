<?php

namespace Modules\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CustomFields\Models\Location;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Services\Validation as ServiceValidation;

class Validation extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages) {
            $skips = [
                'totalItem',
                'addItem',
                'import',
                'payment',
                //'customer',
                //'vendor',
                //'field',
                'calculate',
            ];

            $path = $this->app['request']->getPathInfo();
            $paths = explode('/', $path);
            $code = $paths[1] . '.' . $paths[2];

            if (isset($paths[3]) && in_array($paths[3], $skips)) {
                return new ServiceValidation($translator, $data, $rules, $messages);
            }

            if($code == "auth.login" || $code == "auth.forgot"){
                return new ServiceValidation($translator, $data, $rules, $messages);
            }

            $location = Location::where('code', $code)->first();

            if (empty($location)) {
                return new ServiceValidation($translator, $data, $rules, $messages);
            }

            $custom_fields = Field::enabled()->orderBy('name')->byLocation($location->id)->get();

            if (empty($custom_fields->count())) {
                return new ServiceValidation($translator, $data, $rules, $messages);
            }

            // Dynamic invoice item sort orders
            if ($code == 'sales.invoices' || $code == 'purchases.bills') {
                $request_items = request()->get('items');

                $sort_orders = [];

                foreach ($request_items as $request_item) {
                    foreach ($request_item as $key => $value) {
                        $sort_orders[] = $key . '_td_input_start';
                        $sort_orders[] = $key . '_td_input_end';
                    }
                    break;
                }
            }

            foreach ($custom_fields as $custom_field) {
                $rule = ($custom_field->rule) ? $custom_field->rule : '';

                $rule_code = $custom_field->code;

                if (!empty($request_items) && in_array($custom_field->fieldLocation->sort_order, $sort_orders)) {
                    $rule_code = 'items.*.' . $custom_field->code;
                }

                if ($custom_field->required) {
                    $rules[$rule_code] = 'required' . '|' . $rule;
                } else {
                    $rules[$rule_code] = $rule;
                }
            }

            return new ServiceValidation($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
