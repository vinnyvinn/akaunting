@extends('layouts.admin')

@section('title', trans('projects::general.title'))

@section('new_button')
	@permission('create-projects')
		<span class="new-button">
			<a href="{{ url('projects/projects/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('projects::general.new') . ' ' . trans_choice('projects::general.project', 1) }}</a>
		</span>
	@endpermission
@endsection

@section('content')
    @if ($projects->count())
        <div class="row mb-4">
            @foreach($projects as $project)
                <div class="col-md-4">
                    <div class="card card-stats">
                        <span>
                            <div class="dropdown card-action-button">
                                <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-primary"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="{{ url('projects/projects/' . $project->id) }}">{{ trans('projects::general.overview') }}</a>
                                    <a class="dropdown-item" href="{{ url('projects/projects/' . $project->id . '/print') }}" target="_blank">{{ trans('general.print') }}</a>
                                    <div class="dropdown-divider"></div>
                                    @permission('update-projects')
                                    <a class="dropdown-item" href="{{ route('projects.projects.edit', $project->id) }}">{{ trans('general.edit') }}</a>
                                    @endpermission
                                    @permission('delete-projects')
                                    @include('projects::layouts.delete_link', ['item' => $project, 'url' => 'projects/projects', 'text' => 'project', 'value' => 'name', 'id' => 'id'])
                                    @endpermission
                                </div>
                            </div>
                        </span>
    
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('projects.projects.show', $project->id) }}">
                                        <h5 class="card-title text-uppercase text-muted mb-0 long-texts text-lg">{{ $project->name }}</h5>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="row">
                            	<div class="col">
                        			@if($project->status == 0)
                        				<span class="h6 mb-0 text-capitalize badge badge-sm badge-warning">{{ trans('projects::general.inprogress') }}</span>
                        			@elseif($project->status == 1)
                        				<span class="h6 mb-0 text-capitalize badge badge-sm badge-success">{{ trans('projects::general.completed') }}</span>
                        			@else
                        				<span class="h6 mb-0 text-capitalize badge badge-sm badge-danger">{{ trans('projects::general.canceled') }}</span>
                        			@endif
                    			</div>
    						</div>
    						
                            <div class="row">
                                <div class="col">
                                    <p class="mt-3 mb-0 text-sm long-texts font-weight-400">
                                        <span>{{ $project->description }}</span>
                                	</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col text-left mt-3">
                                    <i class="fas fa-lg fa-file-invoice mr-1"></i>
                                    <span class="text-sm align-top">{{ trans_choice('projects::general.invoice', 2) }}:&nbsp;{{ $project->invoices()->count() }}</span>
                                </div>
                                <div class="col text-left mt-3">
                                    <i class="fa fa-lg fa-plus mr-1"></i>
                                    <span class="text-sm align-top">{{ trans_choice('projects::general.revenue', 2) }}:&nbsp;{{ $project->income_transactions()->count() }}</span>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col text-left mt-3">
                                    <i class="far fa-lg fa-file-alt mr-1"></i>
    								<span class="text-sm align-top">{{ trans_choice('projects::general.bill', 2) }}:&nbsp;{{ $project->bills()->count() }}</span>
                                </div>
                                <div class="col text-left mt-3">
                                    <i class="fa fa-lg fa-minus mr-1"></i>
                                    <span class="text-sm align-top">{{ trans_choice('projects::general.payment', 2) }}:&nbsp;{{ $project->expense_transactions()->count() }}</span>
                                </div>
                            </div>
                         
                         	<div class="row">
                         		<div class="col-md-3 mt-3">
                                    <a href="{{ route('projects.projects.show', $project->id) . '?activetab=' . strtolower(trans_choice('projects::general.transaction', 2)) }}">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                    </a>
                                </div>
                         		<div class="col-md-3 mt-3">
                                    <a href="{{ route('projects.projects.show', $project->id) . '?activetab=' . strtolower(trans_choice('projects::general.activity', 2)) }}">
                                        <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                            <i class="fa fa-tasks"></i>
                                        </div>
                                    </a>
                                </div>
                         		<div class="col-md-3 mt-3">
                                    <a href="{{ route('projects.projects.show', $project->id) . '?activetab=' . strtolower(trans_choice('projects::general.task', 2)) }}">
                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                            <i class="far fa-check-circle"></i>
                                        </div>
                                    </a>
                                </div>
                         		<div class="col-md-3 mt-3">
                                    <a href="{{ route('projects.projects.show', $project->id) . '?activetab=' . strtolower(trans('projects::general.discussion')) }}">
                                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                            <i class="far fa-comment-dots"></i>
                                        </div>
                                    </a>
                                </div>
                         	</div>
                         
    						<div class="row">
    							<div class="col mt-3 text-sm">
    								{{ trans_choice('general.users', 2) }}
    							</div>
    						</div>
    						
    						<div class="row">
    							<div class="col mt-1 long-texts">
    								@foreach($project->users as $projectUser)
    									<div class="icon icon-shape bg-white rounded-circle shadow ml-2 mb-2 mt-2">
        									@if (App\Models\Auth\User::where('id', $projectUser->user_id)->first()->picture) 
            									@if (setting('general.use_gravatar', '0') == '1') 
            										<img src="{{ App\Models\Auth\User::where('id', $projectUser->user_id)->first()->picture }}" class="img-circle img-sm" alt="User Image"> 
            									@else 
            										<img src="{{ Storage::url(App\Models\Auth\User::where('id', $projectUser->user_id)->first()->picture->id) }}" class="img-circle img-sm" alt="User Image"> 
            									@endif 
        									@else
                            					<i class="fas fa-user"></i>
        									@endif    				
                    					</div>
    								@endforeach
    							</div>
    						</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        @include('projects::projects.empty')  
    @endif
    
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Projects/Resources/assets/js/projects.min.js?v=' . version('short')) }}"></script>
@endpush
