<?php

namespace Modules\Crm\Traits;

trait Activities
{
    public function getActivitiesByClass($class)
    {
        $notes = $class->notes()->orderBy('created_at', 'desc')->get();
        $emails = $class->emails()->orderBy('created_at', 'desc')->get();
        $logs = $class->logs()->orderBy('created_at', 'desc')->get();
        $schedules = $class->schedules()->orderBy('created_at', 'desc')->get();
        $tasks = $class->tasks()->orderBy('created_at', 'desc')->get();
        $deals = $class->deals()->orderBy('created_at', 'desc')->get();

        $items = (object) array_merge_recursive((array) $notes, (array) $emails, (array) $logs, (array) $schedules, (array) $tasks, (array) $deals);

        return $this->getTimelineData($items);
    }

    public function getTimelineData($items, $page = false)
    {
        $data = [];
        $date_format = $this->getCompanyDateFormat();

        foreach ($items as $item) {
            foreach ($item as $row) {
                $class_name = $class_id = $type = $name = $badge_class = $icon = null;

                switch ($row->getTable()) {
                    case 'crm_notes':
                        $type = 'notes';

                        $name = trans_choice('crm::general.notes', 1);

                        if ($page) {
                            if ($row->noteable_type == 'Modules\Crm\Models\Contact') {
                                $name_type = trans_choice('crm::general.contacts', 1);
                            } else if ($row->noteable_type == 'Modules\Crm\Models\Company') {
                                $name_type = trans_choice('crm::general.companies', 1);
                            } else {
                                $name_type = trans_choice('crm::general.deals', 1);
                            }

                            $name = trans('crm::general.note_type', ['type' => $name_type]);
                        }

                        if ($row->noteable_type == 'Modules\Crm\Models\Contact') {
                            $class_name = 'contacts';
                        } else if ($row->noteable_type == 'Modules\Crm\Models\Company') {
                            $class_name = 'companies';
                        } else {
                            $class_name = 'deals';
                        }

                        $class_id = $row->noteable_id;

                        $badge_class = 'badge-info';
                        $icon = 'far fa-sticky-note';

                        break;
                    case 'crm_emails':
                        $type = 'emails';

                        $name = trans_choice('crm::general.emails', 1);

                        if ($page) {
                            if ($row->emailable_type == 'Modules\Crm\Models\Contact') {
                                $name_type = trans_choice('crm::general.contacts', 1);
                            } else if ($row->emailable_type == 'Modules\Crm\Models\Company') {
                                $name_type = trans_choice('crm::general.companies', 1);
                            } else {
                                $name_type = trans_choice('crm::general.deals', 1);
                            }

                            $name = trans('crm::general.email_type', ['type' => $name_type]);
                        }

                        if ($row->emailable_type == 'Modules\Crm\Models\Contact') {
                            $class_name = 'contacts';
                        } else if ($row->emailable_type == 'Modules\Crm\Models\Company') {
                            $class_name = 'companies';
                        } else {
                            $class_name = 'deals';
                        }

                        $class_id = $row->emailable_id;

                        $badge_class = 'badge-success';
                        $icon = 'far fa-envelope';

                        break;
                    case 'crm_logs':
                        $type = 'logs';

                        $name = trans_choice('crm::general.logs', 1);

                        if ($page) {
                            $name_type = trans_choice('crm::general.contacts', 1);

                            if ($row->logable_type != 'Modules\Crm\Models\Contact') {
                                $name_type = trans_choice('crm::general.companies', 1);
                            }

                            $name = trans('crm::general.logs_type', ['type' => $name_type]);
                        }

                        $class_name = 'contacts';

                        if ($row->logable_type != 'Modules\Crm\Models\Contact') {
                            $class_name = 'companies';
                        }

                        $class_id = $row->logable_id;

                        $badge_class = 'badge-warning';
                        $icon = 'far fa-list-alt';

                        break;
                    case 'crm_schedules':
                        $type = 'schedules';

                        $name = trans_choice('crm::general.schedules', 1);

                        if ($page) {
                            $name_type = trans_choice('crm::general.contacts', 1);

                            if ($row->scheduleable_type != 'Modules\Crm\Models\Contact') {
                                $name_type = trans_choice('crm::general.companies', 1);
                            }

                            $name = trans('crm::general.schedule_type', ['type' => $name_type]);
                        }

                        $class_name = 'contacts';

                        if ($row->scheduleable_type != 'Modules\Crm\Models\Contact') {
                            $class_name = 'companies';
                        }

                        $class_id = $row->scheduleable_id;

                        $badge_class = 'badge-primary';
                        $icon = 'fas fa-sync';

                        break;
                    case 'crm_tasks':
                        $type = 'tasks';

                        $name = trans_choice('crm::general.tasks', 1);

                        if ($page) {
                            $name_type = trans_choice('crm::general.contacts', 1);

                            if ($row->taskable_type != 'Modules\Crm\Models\Contact') {
                                $name_type = trans_choice('crm::general.companies', 1);
                            }

                            $name = trans('crm::general.task_type', ['type' => $name_type]);
                        }

                        $class_name = 'contacts';

                        if ($row->taskable_type != 'Modules\Crm\Models\Contact') {
                            $class_name = 'companies';
                        }

                        $class_id = $row->taskable_id;

                        $badge_class = 'badge-danger';
                        $icon = 'fas fa-tasks';

                        break;

                    case 'crm_deal_activities':
                        $type = 'deals';

                        $name = trans_choice('crm::general.deals', 1);

                        if ($page) {
                            $name_type = trans_choice('crm::general.deals', 1);

                            $name = trans('crm::general.deal_activities', ['type' => $name_type]);
                        }

                        $class_name = 'deals';
                        $class_id = $row->id;

                        $badge_class = 'badge-dark';
                        $icon = 'far fa-handshake';

                        break;
                    default:

                }

                $data[] = [
                    'class_name' => $class_name,
                    'class_id' => $class_id,
                    'type' => $type,
                    'type_id' => $row->id,
                    'name' => $name,
                    'created' => ($row->createdBy) ? $row->createdBy->name : trans('general.na'),
                    'date' => \Date::parse($row->created_at)->format($date_format),
                    'badge_class' => $badge_class,
                    'icon' => $icon,
                ];
            }
        }

        $activities = collect($data)->sortBy('created_at');

        return $activities;
    }

    public function getActivityTypes()
    {
        return  [
            'all' => trans('general.all'),
            'email' => trans('crm::general.email'),
            'note' => trans('crm::general.note'),
            'log' => trans_choice('crm::general.logs',1),
            'schedule' => trans_choice('crm::general.schedule', 1),
            'task' => trans('crm::general.task'),
            'deal_activities' => trans('crm::general.deal_activities'),
        ];
    }
}
