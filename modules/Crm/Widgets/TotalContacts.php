<?php

namespace Modules\Crm\Widgets;

use App\Abstracts\Widget;
use Modules\Crm\Models\Contact;

class TotalContacts extends Widget
{
    public $default_name = 'crm::widgets.total_contacts';

    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function show()
    {
        $contacts = Contact::all()->count();

        return $this->view('crm::widgets.total_contacts', [
            'contacts' => $contacts,
        ]);
    }
}
