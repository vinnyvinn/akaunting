
<div class="nav-wrapper">
    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
        <li class="nav-item ">
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#all" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">
                <i class= mr-2"></i>{{ trans('general.all') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#activity_line" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">
                <i class="mr-2"></i>{{ trans_choice('crm::general.activities', 2) }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">
                <i class="mr-2"></i> {{ trans_choice('general.notes', 2) }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#emails" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">
                <i class="mr-2"></i>{{ trans_choice('crm::general.emails', 2) }}
            </a>
        </li>
    </ul>
</div>

<div class="card tab-content">
    <div class="tab-pane tab-margin active" id="all">
        <div class="table table-responsive">
            <div class="col-md-12 no-padding-right">
                <crm-activities
                    :data="{{ json_encode($timelines['all']) }}"
                    :status-text="'{{ trans_choice('general.statuses', 1) }}'"
                    :show-button-text="'{{ trans('general.show') }}'"
                    :edit-button-text="'{{ trans('general.edit') }}'"
                    :delete-button-text="'{{ trans('general.delete') }}'"
                    :no-records-text="'{{ trans('general.no_records') }}'"
                    @permission('update-crm-contacts')
                    :edit-button-status="true"
                    @endpermission
                    @permission('delete-crm-contacts')
                    :delete-button-status="true"
                    @endpermission
                    :delete-text="'{{ trans('crm::general.modal.delete.title') }}'"
                    :delete-text-message="'{{ trans('crm::general.modal.delete.message') }}'"
                    :save-button-text="'{{ trans('general.save') }}'"
                    :cancel-button-text="'{{ trans('general.cancel') }}'">
                </crm-activities>
            </div>
        </div>
    </div>

    <div class="tab-pane tab-margin" id="activity_line">
        <div class="table table-responsive">
            <div class="col-md-12 no-padding-right">
                <crm-activities
                    :data="{{ json_encode($timelines['activities']) }}"
                    :status-text="'{{ trans_choice('general.statuses', 1) }}'"
                    :show-button-text="'{{ trans('general.show') }}'"
                    :edit-button-text="'{{ trans('general.edit') }}'"
                    :delete-button-text="'{{ trans('general.delete') }}'"
                    :no-records-text="'{{ trans('general.no_records') }}'"
                    @permission('update-crm-contacts')
                    :edit-button-status="true"
                    @endpermission
                    @permission('delete-crm-contacts')
                    :delete-button-status="true"
                    @endpermission
                    :delete-text="'{{ trans('crm::general.modal.delete.title') }}'"
                    :delete-text-message="'{{ trans('crm::general.modal.delete.message') }}'"
                    :save-button-text="'{{ trans('general.save') }}'"
                    :cancel-button-text="'{{ trans('general.cancel') }}'">
                </crm-activities>
            </div>
        </div>
    </div>

    <div class="tab-pane tab-margin" id="notes">
        <div class="table table-responsive">
            <div class="col-md-12 no-padding-right">
                <crm-activities
                    :data="{{ json_encode($timelines['notes']) }}"
                    :status-text="'{{ trans_choice('general.statuses', 1) }}'"
                    :show-button-text="'{{ trans('general.show') }}'"
                    :edit-button-text="'{{ trans('general.edit') }}'"
                    :delete-button-text="'{{ trans('general.delete') }}'"
                    :no-records-text="'{{ trans('general.no_records') }}'"
                    @permission('update-crm-contacts')
                    :edit-button-status="true"
                    @endpermission
                    @permission('delete-crm-contacts')
                    :delete-button-status="true"
                    @endpermission
                    :delete-text="'{{ trans('crm::general.modal.delete.title') }}'"
                    :delete-text-message="'{{ trans('crm::general.modal.delete.message') }}'"
                    :save-button-text="'{{ trans('general.save') }}'"
                    :cancel-button-text="'{{ trans('general.cancel') }}'">
                </crm-activities>
            </div>
        </div>
    </div>

    <div class="tab-pane tab-margin" id="emails">
        <div class="table table-responsive">
            <div class="col-md-12 no-padding-right">
                <crm-activities
                    :data="{{ json_encode($timelines['emails']) }}"
                    :status-text="'{{ trans_choice('general.statuses', 1) }}'"
                    :show-button-text="'{{ trans('general.show') }}'"
                    :edit-button-text="'{{ trans('general.edit') }}'"
                    :delete-button-text="'{{ trans('general.delete') }}'"
                    :no-records-text="'{{ trans('general.no_records') }}'"
                    @permission('update-crm-contacts')
                    :edit-button-status="true"
                    @endpermission
                    @permission('delete-crm-contacts')
                    :delete-button-status="true"
                    @endpermission
                    :delete-text="'{{ trans('crm::general.modal.delete.title') }}'"
                    :delete-text-message="'{{ trans('crm::general.modal.delete.message') }}'"
                    :save-button-text="'{{ trans('general.save') }}'"
                    :cancel-button-text="'{{ trans('general.cancel') }}'">
                </crm-activities>
            </div>
        </div>
    </div>
</div>

