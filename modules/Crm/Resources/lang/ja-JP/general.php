<?php

return [

    'name'                  => 'CRM',
    'description'           => 'カスタマージャーニーのすべてのステップを監視および分析します',

    'contacts'              => '連絡先|連絡先',
    'companies'             => '会社|会社',
    'activities'            => 'アクティビティ|アクティビティ',
    'deals'                 => 'ディール|取引',
    'logs'                  => 'ログ|ログ',
    'notes'                 => 'ノート|ノート',
    'tasks'                 => 'タスク|タスク',
    'emails'                => 'メール|メール',
    'schedules'             => 'スケジュール|スケジュール',

    'information' => 'インフォメーション',
    'owner' => '所有者',
    'users' => 'ユーザー',
    'mobile' => '携帯電話',
    'agents' => 'エージェント|エージェント',
    'fax_number' => 'Fax番号',
    'source' => 'ソース',
    'birthday_at' => '誕生日',
    'log_activity' => 'ログアクティビティ',

    'schedule' => 'スケジュール',
    'task' => 'タスク',
    'note' => 'ノート',
    'email' => 'メールアドレス',
    'log' => 'ログ',

    'schedule_type' => ':type スケジュール',
    'task_type' => ':type タスク',
    'note_type' => ':type ノート',
    'email_type' => ':type Eメール',
    'logs_type' => ':type ログ',

    'activity' => 'アクティビティ',
    'activity_description' =>'CRM の活動レポート',
    'growth' => '生育',
    'growth_description' =>'CRM の成長レポート',

    'body' => 'メールの本文を入力してください...',
    'subject' => '件名',
    'time' => '時間',
    'start_time' => '始まる時間',
    'end_time' => '終了時間',
    'start_date' => '開始日',
    'end_date' => '終了日',
    'deal_value' => '取引価値',
    'close_date' => 'クローズ予定日',
    'closed_at' =>'休業',
    'duration' => 'デュレイション',
    'assigned' => '割り当て済み',
    'mark_as_done' => '完了としてマークする',
    'add_activity' => 'アクティビティを追加',
    'take_notes' => 'メモする',
    'done' => '完了',
    'field_title' => 'タイトル',
    'open_activities' => 'オープン活動|オープンしている活動',
    'competitors' => '競合他社|競合他社',
    'strengths' => '強度',
    'weaknesses' => '弱点',
    'deal_activities' => 'ディールアクティビティ',
    'add_activity_type' => '新しい活動の種類の追加',
    'edit_activity_type' => '活動タイプの編集',
    'add_stage' => '新しいステージの追加',
    'add_pipeline'=>'新しいパイプラインの追加',
    'won' => 'ウォン',
    'lost' => '紛失',
    'trash' => 'ゴミ箱',
    'reopen' => '再開する',
    'pipeline' => "パイプライン",
    'count' => "カウント",
    'open' => "開く",
    'life_stage' => 'ライフステージ',
    'edit_stage' => 'ステージの編集',
    'add_company' => '会社の追加',
    'add_contact' => '連絡先の追加',
    'link_crm'=>'CRM にリンクしますか?',
    'type' => 'タイプ',
    'contact' => 'コンタクト|連絡先',
    'company' => '会社|会社',
    'born_at' => '生まれ',

    'stage' => [
        'customer' => 'カスタマー',
        'lead' => 'リード',
        'opportunity' => '機会',
        'subscriber' => 'サブスクライバー',
        'title' => 'ステージ',
        'not_change' => '変えないで'
    ],

    'sources' => [
        'advert' => 'アドバタイズ',
        'chat' => 'チャット',
        'contact_form' => 'お問い合わせフォーム',
        'employee_referral' => '従業員紹介',
        'external_referral' => '外部紹介',
        'marketing_campaign' => 'マーケティング キャンペーン',
        'newsletter' => 'ニュースレター',
        'online_store' => 'オンライン ストア',
        'optin_form' => 'オプティンフォーム',
        'partner' => 'パートナー',
        'phone' => '電話',
        'public_relations' => 'パブリックリレーション',
        'sales_mail_alias' => 'セールス メール エイリアス',
        'search_engine' => '検索エンジン',
        'seminar_internal' => 'セミナー内部',
        'seminar_partner' => 'セミナーパートナー',
        'social_media' => 'ソーシャルメディア',
        'trade_show' => 'トレードショー',
        'web_download' => 'Webダウンロード',
        'web_research' => 'ウェブリサーチ'
    ],

    'log_type' => [
        'call' => '通話をログに記録する',
        'meeting' => '会議のログ記録',
        'email' => 'メールを記録する',
        'sms' => 'Smsを記録する'
    ],

    'report' => [
        'activity_report' => '活動報告',
        'growth_report' => '成長レポート'
    ],

    'message' => [
       'disable_code' => '警告: を無効にすることはできません <b>:ネーム</b> 持っているから :テキスト関連.',
       'addednote' => '追加されたメモ！',
    ],

    'modal' => [
        'title' => 'ショー :field',
        'delete' => [
            'title' => 'アクティビティを削除',
            'message' => '本気ですか？',
        ],
        'edit' => [
            'title' => '編集 :field',
        ],
    ],

    'activity_types' => [
        'call' => 'コール',
        'meeting' => 'ミーティング',
        'dead_line' => '締め切り',
        'email' => 'Eメール',
    ],

    'pipeline_stages' => [
        'proposal_made' => '提案された',
        'lead_in' =>  'リードイン',
        'contact_made' => 'お問い合わせ',
        'demo_scheduled' => 'デモ予定',
        'negotitions_started' => '交渉開始',
    ]
];
