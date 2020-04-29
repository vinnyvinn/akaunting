@extends('layouts.print')

@section('title', trans('projects::general.summary'))

@section('content')
	<div class="text-center mt-3">
		<h3 >{{ trans_choice('projects::general.project', 1) . ' ' . trans('projects::general.summary') }}</h3>
	</div>

	<div class="pl-5">
		<h4>{{ trans_choice('projects::general.project', 1) . ' ' . trans('general.name') . ': ' . $project->name }}</h4>
	</div>

	<div class="pl-5">
		<h4>{{ trans_choice('projects::general.project', 1) . ' ' . trans('general.description') . ': ' . $project->description }}</h4>
	</div>

	<div class="pl-5">
		<h4>{{ trans_choice('general.customers', 1) . ': ' . $project->customer->name }}</h4>
	</div>

	<div class="pl-5">
		@if($project->status == 0)
			<h4>{{ trans('projects::general.status') . ': ' . trans('projects::general.inprogress') }}</h4>
		@elseif($project->status == 1)
			<h4>{{ trans('projects::general.status') . ': ' . trans('projects::general.completed') }}</h4>
		@else
			<h4>{{ trans('projects::general.status') . ': ' . trans('projects::general.canceled') }}</h4>
		@endif
	</div>

	<div class="pl-5">
		<h4>{{ trans('projects::general.profit') . ' ' . trans('projects::general.margin') . ': ' . $profitMarginOfProject . '%' }}</h4>
	</div>

	<div class="table table-responsive">
		<table class="table table-striped table-hover" id="tbl-transactions">
			<thead>
				<tr>
					<th>{{ trans('general.date') }}</th>
					<th>{{ trans_choice('projects::general.type', 1) }}</th>
					<th>{{ trans_choice('general.description', 1) }}</th>
					<th class="text-right amount-space">{{ trans('general.amount') }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach($transactions['transactionList'] as $item)
				<tr>
					<td class="text-center">{{ Date::parse($item['paid_at'])->format($date_format) }}</td>
					<td class="text-center">{{ $item['type'] }}</td>
					<td>{{ $item['description'] }}</td>
					<td class="text-right amount-space">{{ $item['transaction_amount'] }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
@endsection