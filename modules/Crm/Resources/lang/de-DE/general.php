<?php

return [

    'name'                  => 'CRM',
    'description'           => 'Monitor and analyze every step of the customer journey',

    'contacts'              => 'Kontakt|Kontakte',
    'companies'             => 'Unternehmen|Unternehmen',
    'activities'            => 'Aktivität|Aktivitäten',
    'deals'                 => 'Deal|Deals',
    'logs'                  => 'Log|Logs',
    'notes'                 => 'Notiz|Notizen',
    'tasks'                 => 'Aufgabe|Aufgaben',
    'emails'                => 'E-Mail|E-Mails',
    'schedules'             => 'Schedule|Schedules',

    'information' => 'Information',
    'owner' => 'Owner',
    'users' => 'Benutzer',
    'mobile' => 'Mobile',
    'agents' => 'Agent|Agenten',
    'fax_number' => 'Fax',
    'source' => 'Quelle',
    'birthday_at' => 'Geburtstag',
    'log_activity' => 'Log Aktivitäten',

    'schedule' => 'Schedule',
    'task' => 'Aufgabe',
    'note' => 'Notiz',
    'email' => 'E-Mail',
    'log' => 'Logs',

    'schedule_type' => ':type Schedule',
    'task_type' => ':type Aufgabe',
    'note_type' => ':type Notiz',
    'email_type' => ':type E-Mail',
    'logs_type' => ':type Log',

    'activity' => 'Aktivität',
    'activity_description' =>'Aktivitätsbericht für CRM',
    'growth' => 'Wachstum',
    'growth_description' =>'Wachstumsbericht für CRM',

    'body' => 'Geben Sie Ihren E-Mail-Body ein...',
    'subject' => 'Betreff',
    'time' => 'Zeit',
    'start_time' => 'Startzeit',
    'end_time' => 'Endzeit',
    'start_date' => 'Startdatum',
    'end_date' => 'Enddatum',
    'deal_value' => 'Deal Value',
    'close_date' => 'Abschluss erwartet bis',
    'closed_at' =>'Closed At',
    'duration' => 'Dauer',
    'assigned' => 'Zugewiesen',
    'mark_as_done' => 'Als gesendet markieren',
    'add_activity' => 'Aktivität hinzufügen',
    'take_notes' => 'Notizen speichern',
    'done' => 'Erledigt',
    'field_title' => 'Titel',
    'open_activities' => 'Aktivität öffnen|Aktivitäten öffnen',
    'competitors' => 'Wettbewerber|Wettbewerber',
    'strengths' => 'Strengths',
    'weaknesses' => 'Weaknesses',
    'deal_activities' => 'Deal Activities',
    'add_activity_type' => 'Add New Activity Type',
    'edit_activity_type' => 'Edit Activity Type',
    'add_stage' => 'Add New Stage',
    'add_pipeline'=>'Add New Pipeline',
    'won' => 'Gewonnen',
    'lost' => 'Verloren',
    'trash' => 'Papierkorb',
    'reopen' => 'Wieder öffnen',
    'pipeline' => "Pipeline",
    'count' => "Zähler",
    'open' => "Öffnen",
    'life_stage' => 'Life Stage',
    'edit_stage' => 'Edit Stage',
    'add_company' => 'Unternehmen hinzufügen',
    'add_contact' => 'Kontakte hinzufügen',
    'link_crm'=>'Link zu CRM?',
    'type' => 'Typ',
    'contact' => 'Contact|Contacts',
    'company' => 'Company|Companies',
    'born_at' => 'Born At',

    'stage' => [
        'customer' => 'Kunde',
        'lead' => 'Lead',
        'opportunity' => 'Opportunity',
        'subscriber' => 'Subscriber',
        'title' => 'Stage',
        'not_change' => 'Nicht ändern'
    ],

    'sources' => [
        'advert' => 'Advertisement',
        'chat' => 'Chat',
        'contact_form' => 'Kontaktformular',
        'employee_referral' => 'Mitarbeiterberichte',
        'external_referral' => 'Externe Referenz',
        'marketing_campaign' => 'Marketing Kampagne',
        'newsletter' => 'Newsletter',
        'online_store' => 'Online Store',
        'optin_form' => 'Optin Forms',
        'partner' => 'Partner',
        'phone' => 'Phone Call',
        'public_relations' => 'Public Relations',
        'sales_mail_alias' => 'Sales Mail Alias',
        'search_engine' => 'Search Engine',
        'seminar_internal' => 'Seminar Internal',
        'seminar_partner' => 'Seminar Partner',
        'social_media' => 'Soziale Medien',
        'trade_show' => 'Trade Show',
        'web_download' => 'Web Download',
        'web_research' => 'Web Research'
    ],

    'log_type' => [
        'call' => 'Log\'s Telefonate',
        'meeting' => 'Log\'s Meetings',
        'email' => 'Log\'s E-Mail',
        'sms' => 'Log\'s SMS'
    ],

    'report' => [
        'activity_report' => 'Aktivitätenberichte',
        'growth_report' => 'Wachstumsbericht'
    ],

    'message' => [
       'disable_code' => 'Warnung: Sie dürfen <b>:name</b> nicht deaktivieren, da :text dazu in Bezug steht.',
       'addednote' => 'Note Added!',
    ],

    'modal' => [
        'title' => 'Show :field',
        'delete' => [
            'title' => 'Delete Activity',
            'message' => 'Are you sure?',
        ],
        'edit' => [
            'title' => 'Edit :field',
        ],
    ],

    'activity_types' => [
        'call' => 'Call',
        'meeting' => 'Meeting',
        'dead_line' => 'Deadline',
        'email' => 'Email',
    ],

    'pipeline_stages' => [
        'proposal_made' => 'Proposal Made',
        'lead_in' =>  'Lead In',
        'contact_made' => 'Contact Made',
        'demo_scheduled' => 'Demo Scheduled',
        'negotitions_started' => 'Negotiations Started',
    ]
];
