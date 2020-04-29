<?php

return [

    'name'                  => 'CRM',
    'description'           => 'Monitor and analyze every step of the customer journey',

    'contacts'              => 'Contact|Contacts',
    'companies'             => 'Company|Companies',
    'activities'            => 'Activity|Activities',
    'deals'                 => 'Deal|Deals',
    'logs'                  => 'Log|Logs',
    'notes'                 => 'Note|Notes',
    'tasks'                 => 'Task|Tasks',
    'emails'                => 'Email|Emails',
    'schedules'             => 'Schedule|Schedules',

    'information' => 'Information',
    'owner' => 'Owner',
    'users' => 'Users',
    'mobile' => 'Mobile',
    'agents' => 'Agent|Agents',
    'fax_number' => 'Fax Number',
    'source' => 'Source',
    'birthday_at' => 'Birth Date',
    'log_activity' => 'Log Activity',

    'schedule' => 'Schedule',
    'task' => 'Task',
    'note' => 'Note',
    'email' => 'Email',
    'log' => 'Log',

    'schedule_type' => ':type Schedule',
    'task_type' => ':type Task',
    'note_type' => ':type Note',
    'email_type' => ':type Email',
    'logs_type' => ':type Log',

    'activity' => 'Activity',
    'activity_description' =>'Activity report for CRM',
    'growth' => 'Growth',
    'growth_description' =>'Growth report for CRM',

    'body' => 'Type your email body...',
    'subject' => 'Subject',
    'time' => 'Time',
    'start_time' => 'Start Time',
    'end_time' => 'End Time',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'deal_value' => 'Deal Value',
    'close_date' => 'Expected Close Date',
    'closed_at' =>'Closed At',
    'duration' => 'Duration',
    'assigned' => 'Assigned',
    'mark_as_done' => 'Mark As Done',
    'add_activity' => 'Add Activity',
    'take_notes' => 'Take Notes',
    'done' => 'Done',
    'field_title' => 'Title',
    'open_activities' => 'Open Activity|Open Activities',
    'competitors' => 'Competitor|Competitors',
    'strengths' => 'Strengths',
    'weaknesses' => 'Weaknesses',
    'deal_activities' => 'Deal Activities',
    'add_activity_type' => 'Add New Activity Type',
    'edit_activity_type' => 'Edit Activity Type',
    'add_stage' => 'Add New Stage',
    'add_pipeline'=>'Add New Pipeline',
    'won' => 'Won',
    'lost' => 'Lost',
    'trash' => 'Trash',
    'reopen' => 'Reopen',
    'pipeline' => "Pipeline",
    'count' => "Count",
    'open' => "Open",
    'life_stage' => 'Life Stage',
    'edit_stage' => 'Edit Stage',
    'add_company' => 'Add Company',
    'add_contact' => 'Add Contact',
    'link_crm'=>'Link to CRM?',
    'type' => 'Type',
    'contact' => 'Contact|Contacts',
    'company' => 'Company|Companies',
    'born_date' => 'Born Date',
    'deal_status_change' =>'Deal Status Changed!',
    'pipeline_stage_change' =>'Pipeline Stage Changed!',
    'activity_type_change' =>'Activity Type Changed!',

    'stage' => [
        'customer' => 'Customer',
        'lead' => 'Lead',
        'opportunity' => 'Opportunity',
        'subscriber' => 'Subscriber',
        'title' => 'Stage',
        'not_change' => 'Do not change'
    ],

    'sources' => [
        'advert' => 'Advertisement',
        'chat' => 'Chat',
        'contact_form' => 'Contact Form',
        'employee_referral' => 'Employee Referral',
        'external_referral' => 'External Referral',
        'marketing_campaign' => 'Marketing Campaign',
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
        'social_media' => 'Social Media',
        'trade_show' => 'Trade Show',
        'web_download' => 'Web Download',
        'web_research' => 'Web Research'
    ],

    'log_type' => [
        'call' => 'Log a Call',
        'meeting' => 'Log a Meeting',
        'email' => 'Log an Email',
        'sms' => 'Log an Sms'
    ],

    'report' => [
        'activity_report' => 'Activity Report',
        'growth_report' => 'Growth Report'
    ],

    'message' => [
       'disable_code' => 'Warning: You are not allowed to disable of <b>:name</b> because it has :text related.',
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
