<?php

namespace Modules\DoubleEntry\Observers\Common;

use App\Abstracts\Observer;
use App\Models\Common\Company as Model;
use Artisan;

class Company extends Observer
{
    /**
     * Listen to the created event.
     *
     * @param  Model  $company
     * @return void
     */
    public function created(Model $company)
    {
        // Create seeds
        Artisan::call('doubleentry:seed', [
            'company' => $company->id,
        ]);
    }
}
