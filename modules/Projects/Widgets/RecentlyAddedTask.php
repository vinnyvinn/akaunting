<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\Task;
use App\Traits\DateTime;

class RecentlyAddedTask extends Widget
{
    use DateTime;
    
    public $default_name = 'projects::general.widgets.recently_added_task_by_project';

    public function show($project = null)
    {
        if ($project) {
            $this->model->name = trans('projects::general.widgets.recently_added_task');
            $ids = collect($project->id);
            $this->views['header'] = 'projects::widgets.standard_header';
        } else {
            $ids = Project::where('company_id', session('company_id'))->pluck('id');
        }

        $tasks = $this->applyFilters(Task::whereIn('project_id', $ids)->orderBy('created_at', 'desc')
            ->take(5))
            ->get();

        return $this->view('projects::widgets.recently_added_task', [
            'tasks' => $tasks,
            'project' => $project,
            'date_format' => $this->getCompanyDateFormat()
        ]);
    }
}
