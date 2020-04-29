@extends('layouts.admin')

@section('title', $project->name)

@section('content')
<div class="row">
    <div class="col">
        <tabs tab-nav-classes="nav-fill flex-column flex-sm-row nav-wrapper" tab-content-classes="shadow" value="{{ $activetab }}">
            <tab-pane title="{{ strtolower(trans('projects::general.overview')) }}">
            	<span slot="title">
                    <i class="fas fa-tachometer-alt"></i>
                    {{ trans('projects::general.overview') }}
                </span>
                
                <div class="card">
                	<div class="card-body">
                		<div class="row">
	                		@widget('Modules\Projects\Widgets\TotalInvoice', $project)
	                		@widget('Modules\Projects\Widgets\TotalRevenue', $project)
	                		@widget('Modules\Projects\Widgets\TotalBill', $project)
	                		@widget('Modules\Projects\Widgets\TotalPayment', $project)
	                		@widget('Modules\Projects\Widgets\TotalActivity', $project)
	                		@widget('Modules\Projects\Widgets\TotalTask', $project)
	                		@widget('Modules\Projects\Widgets\TotalDiscussion', $project)
	                		@widget('Modules\Projects\Widgets\TotalUser', $project)
	                		@widget('Modules\Projects\Widgets\ProjectLineChart', $project)
	                		@widget('Modules\Projects\Widgets\LatestIncome', $project)
	                		@widget('Modules\Projects\Widgets\ActiveDiscussion', $project)
	                		@widget('Modules\Projects\Widgets\RecentlyAddedTask', $project)
                		</div>
                	</div>
                </div>
         	</tab-pane>
        
            <tab-pane title="{{ strtolower(trans_choice('projects::general.activity', 2)) }}">
            	<span slot="title">
                    <i class="fas fa-stream"></i>
                    {{ trans_choice('projects::general.activity', 2) }}
                </span>
                
				<project-activities
					:activities="{{ json_encode($activities['allActivities']) }}"
				></project-activities>
         	</tab-pane>

            <tab-pane title="{{ strtolower(trans_choice('projects::general.transaction', 2)) }}">
            	<span slot="title">
                    <i class="far fa-file"></i>
                    {{ trans_choice('projects::general.transaction', 2) }}
                </span>
                
            	<project-transactions
            		:transactions="{{ json_encode($transactions['transactionList']) }}"
					:text-filter="'{{ trans('general.filter') }}'"
					:text-filter-placeholder="'{{ trans('general.form.select.field', ['field' => 'Type']) }}'"
					:filter-options="{{ json_encode($doc_type) }}"
					:route-export="'{{ route('projects.transactions.export', $project->id) }}'"
					:text-export="'{{ trans('general.export') }}'"
					:text-column-type="'{{ trans_choice('projects::general.type', 1) }}'"
					:text-column-category="'{{ trans_choice('general.categories', 1) }}'"
					:text-column-description="'{{ trans_choice('general.description', 1) }}'" 
					:text-column-date="'{{ trans('general.date') }}'"           	
					:text-column-amount="'{{ trans('general.amount') }}'"
					:text-no-records="'{{ trans('general.no_records') }}'"
					:per-page-limit-options="{{ json_encode($itemLimits) }}"
            	></project-transactions>
         	</tab-pane>
         	
			@permission('read-project-tasks')
            <tab-pane title="{{ strtolower(trans_choice('projects::general.task', 2)) }}">
            	<span slot="title">
                    <i class="fas fa-tasks"></i>
                    {{ trans_choice('projects::general.task', 2) }}
                </span>
                
                <project-tasks
					:project-id="{{ $project->id }}"
					:tasks="{{ json_encode($tasks['taskList']) }}"
					:task-priorities="{{ json_encode($tasks['taskPriorities']) }}"
					:members="{{ json_encode($members) }}"
					:task-statuses="{{ json_encode($tasks['taskStatusList']) }}"
                	:text-new-task="'{{ trans('projects::general.new') . ' ' . trans_choice('projects::general.task', 1) }}'"
					:text-edit-task="'{{ trans('projects::general.edit') . ' ' . trans_choice('projects::general.task', 1) }}'"
					:text-delete-task="'{{ trans('general.delete') . ' ' . trans_choice('projects::general.task', 1) }}'"
                	:text-subtask="'{{ trans_choice('projects::general.subtask', 1) }}'"
                	:text-new-subtask="'{{ trans('projects::general.new') . ' ' . trans_choice('projects::general.subtask', 1) }}'"
                	:text-edit-subtask="'{{ trans('projects::general.edit') . ' ' . trans_choice('projects::general.subtask', 1) }}'"
                	:text-delete-subtask="'{{ trans('general.delete') . ' ' . trans_choice('projects::general.subtask', 1) }}'"
					:text-no-records="'{{ trans('general.no_records') }}'"
					:text-save="'{{ trans('general.save') }}'"
					:text-cancel="'{{ trans('general.cancel') }}'"
					:text-delete="'{{ trans('general.delete') }}'"
					:text-delete-message="'{{ trans('general.confirm') . ' ' . trans('general.delete') }}'"
					:text-name="'{{ trans('general.name') }}'"
					:text-enter-name="'{{ trans('general.form.enter', ['field' => trans('general.name')]) }}'"
					:text-description="'{{ trans('general.description') }}'"
					:text-enter-description="'{{ trans('general.form.enter', ['field' => trans('general.description')]) }}'"
					:text-deadline="'{{ trans('projects::general.deadline') }}'"
					:text-priority="'{{ trans_choice('projects::general.priority', 1) }}'"
					:text-enter-priority="'{{ trans('general.form.enter', ['field' => trans_choice('projects::general.priority', 1)]) }}'"
					:text-member="'{{ trans_choice('projects::general.member', 2) }}'"
					:text-enter-member="'{{ trans('general.form.enter', ['field' => trans_choice('projects::general.member', 2)]) }}'"
					:text-status="'{{ trans('projects::general.status') }}'"
					:text-enter-status="'{{ trans('general.form.enter', ['field' => trans('projects::general.status')]) }}'"
					:text-message-unknown-error="'{{ trans('projects::messages.error.unknown') }}'"
					:text-actions="'{{ trans('general.actions') }}'"
					:per-page-limit-options="{{ json_encode($itemLimits) }}"
					:permission-create-project-tasks="{{ user()->can('create-project-tasks') ? 'true' : 'false' }}"
					:permission-update-project-tasks="{{ user()->can('update-project-tasks') ? 'true' : 'false' }}"
					:permission-delete-project-tasks="{{ user()->can('delete-project-tasks') ? 'true' : 'false' }}"
					:permission-create-project-subtasks="{{ user()->can('create-project-subtasks') ? 'true' : 'false' }}"
					:permission-read-project-subtasks="{{ user()->can('read-project-subtasks') ? 'true' : 'false' }}"
					:permission-update-project-subtasks="{{ user()->can('update-project-subtasks') ? 'true' : 'false' }}"
					:permission-delete-project-subtasks="{{ user()->can('delete-project-subtasks') ? 'true' : 'false' }}"
					:route-task-store="'{{ route('projects.tasks.store') }}'"
					:route-task-update="'{{ route('projects.tasks.update', '#') }}'"
					:route-task-delete="'{{ route('projects.tasks.destroy', '#') }}'"
					:route-subtask-store="'{{ route('projects.subtasks.store') }}'"
					:route-subtask-update="'{{ route('projects.subtasks.update', '#') }}'"
					:route-subtask-delete="'{{ route('projects.subtasks.destroy', '#') }}'"
                ></project-tasks>
         	</tab-pane>
			@endpermission
			
			@permission('read-project-discussions')
            <tab-pane title="{{ strtolower(trans('projects::general.discussion')) }}">
            	<span slot="title">
                    <i class="far fa-comments"></i>
                    {{ trans_choice('projects::general.discussion', 2) }}
                </span>

				<project-discussions
					:project-id="{{ $project->id }}"
					:discussions="{{ json_encode($discussions['discussionList']) }}"
					:text-new-discussion="'{{ trans('projects::general.new') . ' ' . trans_choice('projects::general.discussion', 1) }}'"
					:text-edit-discussion="'{{ trans('projects::general.edit') . ' ' . trans_choice('projects::general.discussion', 1) }}'"
					:text-delete-discussion="'{{ trans('general.delete') . ' ' . trans_choice('projects::general.discussion', 1) }}'"
					:text-discussion-feed="'{{ trans_choice('projects::general.discussion', 1) . ' ' . trans('projects::general.feed') }}'"
					:text-no-records="'{{ trans('general.no_records') }}'"
					:text-save="'{{ trans('general.save') }}'"
					:text-cancel="'{{ trans('general.cancel') }}'"
					:text-delete="'{{ trans('general.delete') }}'"
					:text-delete-message="'{{ trans('general.confirm') . ' ' . trans('general.delete') }}'"
					:text-subject="'{{ trans('projects::general.subject') }}'"
					:text-enter-subject="'{{ trans('general.form.enter', ['field' => trans('projects::general.subject')]) }}'"
					:text-description="'{{ trans('general.description') }}'"
					:text-enter-description="'{{ trans('general.form.enter', ['field' => trans('general.description')]) }}'"
					:text-column-subject="'{{ trans('projects::general.subject') }}'"
					:text-column-created-by="'{{ trans('projects::general.createdby') }}'"
					:text-column-likes="'{{ trans_choice('projects::general.like', 2) }}'"
					:text-column-comments="'{{ trans_choice('projects::general.comment', 2) }}'"
					:text-column-last-activity="'{{ trans('projects::general.last') . ' ' . trans_choice('projects::general.activity', 1) }}'"
					:text-column-actions="'{{ trans('general.actions') }}'"
					:text-comment-placeholder="'{{ trans('general.form.enter', ['field' => trans_choice('projects::general.comment', 1)]) }}'"
					:text-message-unknown-error="'{{ trans('projects::messages.error.unknown') }}'"
					:per-page-limit-options="{{ json_encode($itemLimits) }}"
					:permission-create-project-discussions="{{ user()->can('create-project-discussions') ? 'true' : 'false' }}"
					:permission-update-project-discussions="{{ user()->can('update-project-discussions') ? 'true' : 'false' }}"
					:permission-delete-project-discussions="{{ user()->can('delete-project-discussions') ? 'true' : 'false' }}"
					:route-store="'{{ route('projects.discussions.store') }}'"
					:route-update="'{{ route('projects.discussions.update', '#') }}'"
					:route-delete="'{{ route('projects.discussions.destroy', '#') }}'"
					:route-list-comments="'{{ route('projects.discussions.comments', '#') }}'"
					:route-discussion-likes="'{{ route('projects.discussions.likes', '#') }}'"
					:route-like-discussion="'{{ route('projects.likes.store') }}'"
					:route-unlike-discussion="'{{ route('projects.likes.destroy', '#') }}'"
					:route-post-comment="'{{ route('projects.comments.store') }}'"
					:user="{{ user() }}"
					@if (App\Models\Auth\User::where('id', user()->id)->first()->picture) 
						@if (setting('default.use_gravatar', '0') == '1') 
							:user-image="'{{ App\Models\Auth\User::where('id', user()->id)->first()->picture }}'" 
						@else 
							:user-image="'{{ Storage::url(App\Models\Auth\User::where('id', user()->id)->first()->picture->id) }}'" 
						@endif
					@else
						:user-image="'{{ asset('public/img/user.png') }}'"
					@endif
				></project-discussions>
         	</tab-pane>
         	@endpermission
        </tabs>
    </div>
</div>
@endsection

@push('scripts_start')
	<script src="{{ asset('modules/Projects/Resources/assets/js/projects.show.min.js?v=' . version('short')) }}"></script>
	<script src="https://unpkg.com/vue"></script>	
@endpush