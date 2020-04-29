@extends('layouts.admin')

@section('title', trans_choice('crm::general.activities',2))

@section('new_button')
    @permission('create-crm-deals')
    <span>
        <a href="{{ route('import.create', ['group' => 'crm', 'type' => 'activities']) }}" class="btn btn-white btn-sm header-button-top">
            <span class="fa fa-upload"></span> &nbsp;{{ trans('import.import') }}
        </a>
    </span>
    @endpermission
    <span>
        <a href="{{ route('crm.activities.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top">
            <span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}
        </a>
    </span>
@endsection

@section('content')

    <div class="card">
        <div class="card-header with-border-0">
            {!! Form::open([
                'url' => 'crm/activities',
                'id' => 'activity',
                'method' => 'GET',
                'files' => true,
                'role' => 'form',
                'class' => 'form-loading-button mb-0',
                'novalidate' => true
            ]) !!}
                <div class="card-filter d-flex align-items-center">
                    <span class="title-filter hidden-xs mr-2">{{ trans('general.search') }}:</span>
                    {!! Form::select('type', $types, request('type'), ['class' => 'form-control input-filter form-control-sm w-auto mr-2']) !!}
                    {!! Form::select('user', $users, request('users'), ['class' => 'form-control input-filter form-control-sm w-auto mr-2']) !!}
                    {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-default btn-sm btn-filter']) !!}
                </div>
            {!! Form::close() !!}
        </div>

        <div class="row">
            <div class="col-md-12 no-padding-right">
                <crm-activities
                    :data="{{ json_encode($activities) }}"
                    :status-text="'{{ trans_choice('general.statuses', 1) }}'"
                    :show-button-text="'{{ trans('general.show') }}'"
                    :edit-button-text="'{{ trans('general.edit') }}'"
                    :delete-button-text="'{{ trans('general.delete') }}'"
                    :no-records-text="'{{ trans('general.no_records') }}'"

                    :edit-button-status="true"
                    :delete-button-status="true"

                    :delete-text="'{{ trans('crm::general.modal.delete.title') }}'"
                    :delete-text-message="'{{ trans('crm::general.modal.delete.message') }}'"
                    :save-button-text="'{{ trans('general.save') }}'"
                    :cancel-button-text="'{{ trans('general.cancel') }}'">
                </crm-activities>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Crm/Resources/assets/js/activities.min.js?v=' . version('short')) }}"></script>
@endpush
