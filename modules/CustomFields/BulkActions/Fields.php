<?php

namespace Modules\CustomFields\BulkActions;

use App\Abstracts\BulkAction;
use Modules\CustomFields\Models\Field;

class Fields extends BulkAction
{
    public $model = Field::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-custom-fields-settings',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-custom-fields-settings',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'update-custom-fields-settings',
        ],
    ];

    public function destroy($request)
    {
        $this->deleteTransactions($request);
    }
}
