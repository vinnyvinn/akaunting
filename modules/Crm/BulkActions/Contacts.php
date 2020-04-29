<?php

namespace Modules\Crm\BulkActions;

use App\Abstracts\BulkAction;
use Modules\Crm\Models\Contact;
use App\Jobs\Common\DeleteContact;
use Modules\Crm\Jobs\DeleteContact as DeleteCrmContact;

class Contacts extends BulkAction
{
    public $model = Contact::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-crm-contacts',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-crm-contacts',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-crm-contacts',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
        ],
    ];

    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            try {
                $this->dispatch(new DeleteContact($item->contact));

                $this->dispatch(new DeleteCrmContact($item));
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }
    }
}
