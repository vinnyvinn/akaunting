<?php
namespace Modules\Projects\Widgets;

use App\Abstracts\Widget;
use Modules\Projects\Models\Discussion;
use Modules\Projects\Models\Project;

class ActiveDiscussion extends Widget
{
    public $default_name = 'projects::general.widgets.active_discussion_by_project';
    
    public function show($project = null)
    {
        if ($project) {
            $this->model->name = trans('projects::general.widgets.active_discussion');
            $ids = collect($project->id);
            $this->views['header'] = 'projects::widgets.standard_header';
        } else {
            $ids = Project::where('company_id', session('company_id'))->pluck('id');
        }

        $discussions = $this->applyFilters(Discussion::whereIn('project_id', $ids)->latest()
            ->take(5))
            ->get();

        return $this->view('projects::widgets.active_discussion', [
            'discussions' => $discussions,
            'project' => $project
        ]);
    }
}
