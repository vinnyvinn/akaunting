<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
	<div class="card bg-gradient-default border-0">
		@if($hasProject == false)
			@include($class->views['header'], ['header_class' => 'border-bottom-0'])
		@endif
		<div class="card-body">
			<div class="row">
				<div class="col-xl-8 col-lg-8 col-md-6 col-sm-10 col-xs-10">
					<h3 class="card-title text-uppercase text-muted mb-0 text-white text-xl long-texts">{{
						trans_choice('projects::general.bill', 2) }}</h3>
					<span class="h2 font-weight-bold mb-0 text-white text-lg">@money($billTotalAmount,
						setting('default.currency'), true)</span>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-6 col-sm-2 col-xs-2">
					<div class="icon icon-shape text-white rounded-circle shadow bg-default">
						<i class="fas fa-receipt"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
