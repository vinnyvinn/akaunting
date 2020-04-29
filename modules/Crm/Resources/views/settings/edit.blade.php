@extends('layouts.admin')

@section('title', trans('crm::general.name'))

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#pipeline" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">
                            <i class= "mr-2"></i>{{ trans_choice('crm::setting.pipeline', 1) }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#activity_type" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">
                            <i class="mr-2"></i>{{ trans_choice('crm::setting.activity_type', 1) }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="tab-content">
                    <div class="tab-pane active" id="pipeline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button @click="onPipeline('{{trans('crm::general.add_pipeline')}}')"
                                        class="btn btn-success btn-sm">
                                        <span class="fa fa-plus"></span> &nbsp;{{ trans('crm::general.add_pipeline') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                @if(!empty($pipeline))
                                    @foreach($pipeline as $line)
                                        <b class="ml-4">{{ $line->name }}</b>
                                        <div class="dropdown">
                                            <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-h text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow">
                                                <button @click="onStage({{ $line->id }}, '{{ trans('crm::general.add_stage')}}')" class="dropdown-item">
                                                    {{ trans('crm::general.add_stage') }}
                                                </button>
                                                @permission('delete-crm-companies')
                                                    <div class="dropdown-divider"></div>
                                                    {!! Form::deleteLink($line, 'crm/settings/pipeline/delete') !!}
                                                @endpermission
                                            </div>
                                        </div>

                                        <div class="card-header with-border">
                                            <div class="pipeline-stage-sortable" id="pipeline-sortable-{{ $line->id }}" style="display: inline-flex; margin-bottom: 10px;">
                                                <input type="hidden" name="pipeline_id" class="hidden-pipeline" value="{{ $line->id }}"/>
                                                <crm-stages :data="{{ json_encode($line->stages()->orderBy('rank', 'asc')->get()) }}"></crm-stages>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="activity_type">
                        <crm-activity-types
                            :add-new-activity-type-text = "'{{ trans('crm::general.add_activity_type') }}'"
                            :deleteText = "'{{ trans('crm::setting.delete_text') }}'"
                            :deleteTextMessage = "'{{ trans('crm::setting.delete_text_message') }}'"
                            :statusText = "'{{ trans('crm::setting.status_text') }}'"
                            :showButtonText = "'{{ trans('crm::setting.show_button_text') }}'"
                            :editButtonText = "'{{ trans('crm::setting.edit_button_text') }}'"
                            :deleteButtonText = "'{{ trans('crm::setting.delete_button_text') }}'"
                            :saveButtonText = "'{{ trans('crm::setting.save_button_text') }}'"
                            :cancelButtonText = "'{{ trans('crm::setting.cancel_button_text') }}'"
                            :noRecordsText = "'{{ trans('crm::setting.no_records_text') }}'"
                            :data="{{ json_encode($activities) }}"
                        ></crm-activity-types>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
<script src="{{ asset('modules/Crm/Resources/assets/js/crm-settings.min.js?v=' . version('short')) }}"></script>
@endpush
