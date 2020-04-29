<?php

return [

    'name'                  => 'CRM',
    'description'           => 'Контроль і аналіз кожного кроку подорожі клієнта',

    'contacts'              => 'Контакт|Контакти',
    'companies'             => 'Компанія|Компанії',
    'activities'            => 'Активна|Активні',
    'deals'                 => 'Пропозиція|Пропозиції',
    'logs'                  => 'Лог|Логи',
    'notes'                 => 'Примітка|Примітки',
    'tasks'                 => 'Завдання|Завдання',
    'emails'                => 'Email|Emails',
    'schedules'             => 'Розклад|Розклади',

    'information' => 'Інформація',
    'owner' => 'Власник',
    'users' => 'Користувачі',
    'mobile' => 'Мобільний',
    'agents' => 'Агент|Агенти',
    'fax_number' => 'Номер факсу',
    'source' => 'Джерело',
    'birthday_at' => 'День народження',
    'log_activity' => 'Активний Лог',

    'schedule' => 'Розклад',
    'task' => 'Завдання',
    'note' => 'Нотатка',
    'email' => 'E-mail',
    'log' => 'Журнал',

    'schedule_type' => ':type Розклад',
    'task_type' => ':type Задача',
    'note_type' => ':type примітка',
    'email_type' => ':type Email',
    'logs_type' => ':type Журнал',

    'activity' => 'Активність',
    'activity_description' =>'Звіт про активність для CRM',
    'growth' => 'Ріст',
    'growth_description' =>'Звіт про зростання з CRM',

    'body' => 'Введіть предмет електронної пошти ...',
    'subject' => 'Тема',
    'time' => 'Час',
    'start_time' => 'Час початку ',
    'end_time' => 'Час закінчення',
    'start_date' => 'Дата початку ',
    'end_date' => 'Дата закінчення',
    'deal_value' => 'Вартість пропозиції',
    'close_date' => 'Очікувана дата завершення',
    'closed_at' =>'Закрити до',
    'duration' => 'Тривалість',
    'assigned' => 'Призначений',
    'mark_as_done' => 'Позначити як розіслану',
    'add_activity' => 'Додати активність',
    'take_notes' => 'Зробити примітку',
    'done' => 'Виконано',
    'field_title' => 'Назва',
    'open_activities' => 'Відкритий захід|Відкриті заходи',
    'competitors' => 'Конкурент|Конкуренти',
    'strengths' => 'Сильні сторони',
    'weaknesses' => 'Слабкі сторони',
    'deal_activities' => 'Активні Дії',
    'add_activity_type' => 'Додати новий тип активності',
    'edit_activity_type' => 'Редагувати дію',
    'add_stage' => 'Додати новий етап',
    'add_pipeline'=>'Додати нову воронку',
    'won' => 'Виграш',
    'lost' => 'Втрачено',
    'trash' => 'Кошик',
    'reopen' => 'Відкрити знову',
    'pipeline' => "Воронка",
    'count' => "Підрахунок",
    'open' => "Відкрити",
    'life_stage' => 'Життєвий етап',
    'edit_stage' => 'Редагувати етап',
    'add_company' => 'Додати компанію',
    'add_contact' => 'Додати контакт',
    'link_crm'=>'Посилання на CRM?',
    'type' => 'Тип',
    'contact' => 'Контакт|Контакти',
    'company' => 'Компанія|Компанії',
    'born_at' => 'Народився в',

    'stage' => [
        'customer' => 'Клієнт',
        'lead' => 'Попередній контакт',
        'opportunity' => 'Угода',
        'subscriber' => 'Підписник',
        'title' => 'Етап',
        'not_change' => 'Не змінюйте'
    ],

    'sources' => [
        'advert' => 'Оголошення',
        'chat' => 'Чат',
        'contact_form' => 'Контактна форма',
        'employee_referral' => 'Направлення працівника',
        'external_referral' => 'Зовнішній працівник',
        'marketing_campaign' => 'Маркетингова кампанія',
        'newsletter' => 'Стрічка новин',
        'online_store' => 'Онлайн магазин',
        'optin_form' => 'Вибрані форми',
        'partner' => 'Партнер',
        'phone' => 'Телефонний дзвінок',
        'public_relations' => 'Служба інформації',
        'sales_mail_alias' => 'Псевдонім пошти відділу продажів',
        'search_engine' => 'Пошукова система',
        'seminar_internal' => 'Внутрішній семінар',
        'seminar_partner' => 'Партнерський семінар',
        'social_media' => 'Соціальні мережі',
        'trade_show' => 'Спеціалізована виставка',
        'web_download' => 'Web-завантаження',
        'web_research' => 'Web-дослідження'
    ],

    'log_type' => [
        'call' => 'Увійти в дзвінок',
        'meeting' => 'Увійти на зустріч',
        'email' => 'Увійти в електронну пошту',
        'sms' => 'Увійти в Sms'
    ],

    'report' => [
        'activity_report' => 'Звіт про діяльність',
        'growth_report' => 'Звіт про зростання'
    ],

    'message' => [
       'disable_code' => 'Увага: Вам не дозволено відключати <b>:name</b> , оскільки воно пов\'язане з :text',
       'addednote' => 'Нотатку додано!',
    ],

    'modal' => [
        'title' => 'Показати :field',
        'delete' => [
            'title' => 'Видалити дію',
            'message' => 'Ви впевнені?',
        ],
        'edit' => [
            'title' => 'Редагувати :field',
        ],
    ],

    'activity_types' => [
        'call' => 'Дзвінок',
        'meeting' => 'Зустріч',
        'dead_line' => 'Крайній термін',
        'email' => 'E-mail',
    ],

    'pipeline_stages' => [
        'proposal_made' => 'Пропозиція зроблена',
        'lead_in' =>  'Перший контакт',
        'contact_made' => 'Зроблено контакт',
        'demo_scheduled' => 'Демо заплановано',
        'negotitions_started' => 'Розпочаті переговори',
    ]
];
