<?php

namespace Modules\Inventory\BulkActions;

use App\Abstracts\BulkAction;
use Modules\Inventory\Models\ItemGroup as ModelsItemGroup;

class ItemGroups extends BulkAction
{
    public $model = ModelsItemGroup::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-banking-accounts',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-banking-accounts',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-banking-accounts',
        ],
    ];

    public function disable($request)
    {
        $item_groups = $this->getSelectedRecords($request);

        foreach ($item_groups as $item_group) {
            try {
                ModelsItemGroup::disable($item_group);
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }
    }

    public function destroy($request)
    {
        $accounts = $this->getSelectedRecords($request);

        foreach ($accounts as $account) {
            try {
                $this->dispatch(new ModelsItemGroup($account));
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }
    }
}
