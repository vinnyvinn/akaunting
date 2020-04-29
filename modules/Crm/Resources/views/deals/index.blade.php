@extends('layouts.admin')

@section('title', trans_choice('crm::general.deals', 2))

@section('new_button')
<button @click="onDeal('{{ trans('general.title.new', ['type' => trans_choice('crm::general.deals', 1)])}}')" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</button>
@endsection

@section('content')
    <div class="card deals">
        <div class="card-header with-border">
            {!! Form::open([
                'method' => 'GET',
                'url' => 'crm/deals',
                'id' => 'deal',
                '@submit.prevent' => 'onSubmit',
                '@keydown' => 'form.errors.clear($event.target.name)',
                'files' => true,
                'role' => 'form',
                'class' => 'form-loading-button',
                'novalidate' => true
            ]) !!}
            <div class="row">
               <div> {!! Form::select('pipeline', $pipelines_select, request('pipeline'), ['class' => 'form-control input-filter input-sm','onchange' => 'this.form.submit()']) !!} </div>
                <div>&nbsp;</div>
               <div>{!! Form::select('status', $status_select,  request('status'), ['class' => 'form-control input-filter input-sm','onchange' => 'this.form.submit()']) !!} </div>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="card-body">
            <div class="container-fluid">
                <crm-deals
                    @edit="onEditDeal($event, 'TestDneme')"
                    :data="{{ json_encode($stages) }}"
                    :contactText="'{{ trans_choice('crm::general.contacts',1) }}'"
                    :companyText="'{{ trans_choice('crm::general.companies',1) }}'">
                </crm-deals>
            </div>
        </div>
    </div>
@endsection

@push('content_content_end')
    <component v-bind:is="deal_html"></component>
@endpush

@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('modules/Crm/Resources/assets/css/crm.css?v=' . version('short')) }}" type="text/css">
@endpush

@push('scripts_start')
    <script>
        var dealStatus = 'false';
        var ownerName = '';
    </script>
    <script src="{{ asset('modules/Crm/Resources/assets/js/deals.min.js?v=' . version('short')) }}"></script>
@endpush
