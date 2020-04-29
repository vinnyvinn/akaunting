@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('projects::general.project', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => array('projects.projects.update', $project->id),
            'method' => 'PATCH',
            'id' => 'project',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'role' => 'form',
            'class' => 'form-loading-button needs-validation',
            'novalidate' => 'true'
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'id-card', ['required' => 'required'], $project->name) }}
                    
                    {{ Form::selectAddNewGroup('customer_id', trans_choice('general.customers', 1), 'fa fa-users', $customers, $project->customer_id) }}
                    
                    {{ Form::textareaGroup('description', trans('general.description'), '', $project->description) }}
                    
                    {{ Form::dateGroup('started_at', trans('general.start') . ' ' . trans('general.date'), 'calendar', ['id' => 'started_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], $project->started_at) }}

                    {{ Form::dateGroup('ended_at', trans('projects::general.deadline'), 'calendar', ['id' => 'ended_at', 'class' => 'form-control', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], $project->ended_at) }}

                    {{ Form::multiSelectAddNewGroup('members', trans_choice('projects::general.member', 2), 'fa fa-users', $users, $members) }}
                    
                    {{ Form::selectGroup('status', trans('projects::general.status'), 'fa fa-hourglass-start', $statuses, $project->status, []) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row float-right">
                    {{ Form::saveButtons('projects/projects') }}
                </div>
            </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Projects/Resources/assets/js/projects.min.js?v=' . version('short')) }}"></script>
@endpush
