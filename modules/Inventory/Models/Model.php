<?php

namespace Modules\Inventory\Models;

use App\Abstracts\Model as AppModel;
use Request;
use Route;

class Model extends AppModel
{
    /**
     * Define the filter provider globally.
     *
     * @return ModelFilter
     */
    public function modelFilter()
    {
        // Check if is api or web
        if (Request::is('api/*')) {
            $arr    = array_reverse(explode('\\', explode('@', app()['api.router']->currentRouteAction())[0]));

            $folder = $arr[1];
            $file   = $arr[0];
        } else {
            list($folder, $file) = explode('/', Route::current()->uri());
        }

        if (empty($folder) || empty($file)) {
            return $this->provideFilter();
        }

        if (strpos($file, '-') !== false) {
            $controllers = explode('-', $file);

            $file = '';

            foreach ($controllers as $controller) {
                $file .= ucfirst($controller);
            }
        }

        $class = '\Modules\Inventory\Filters\\' . ucfirst($file);

        return $this->provideFilter($class);
    }

    /**
     * Global company relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo('Modules\Inventory\Models\Item', 'id', 'item_id');
    }
}
