<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use Modules\Projects\Models\Task;

class TotalTask extends Widget
{

    public $default_name = 'projects::general.widgets.total_task';

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-3'
        ];
    }

    public function show($project = null)
    {
        $taskTotal = 0;

        if ($project) {
            $hasProject = true;
            $taskTotal = $project->tasks->count();
            $this->views['header'] = 'projects::widgets.standard_header';
        } else {
            $hasProject = false;
            $taskTotal = Task::where('company_id', session('company_id'))->count();
            $this->views['header'] = 'projects::widgets.stats_header';
        }

        return $this->view('projects::widgets.total_task', [
            'taskTotal' => $taskTotal,
            'hasProject' => $hasProject,
        ]);
    }
}
