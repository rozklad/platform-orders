<style type="text/css">
.stepwizard-step p {
    margin-top: 10px;    
}

.stepwizard-row {
    display: table-row;
}

.stepwizard {
    display: table;     
    width: 100%;
    position: relative;
    table-layout: fixed;
    margin-top: 20px;
    margin-bottom: 20px;
}

.stepwizard-step button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important;
}

.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-order: 0;
    
}

.stepwizard-step {    
    display: table-cell;
    text-align: center;
    position: relative;
    width: 100%;
}

.stepwizard-step:first-child:before {
	position: absolute;
	z-index: 1;
	background-color: #fff;
	content: "";
	display: block;
	width: 50%;
	height: 100%;
	left: 0;
	top: 0;
}

.stepwizard-step:last-child:before {
	position: absolute;
	z-index: 1;
	background-color: #fff;
	content: "";
	display: block;
	width: 50%;
	height: 100%;
	right: 0;
	top: 0;
}

.stepwizard-step .btn, .stepwizard-step p {
	position: relative;
	z-index: 2;
}

.btn-circle {
	width: 30px;
	height: 30px;
	text-align: center;
	padding: 6px 0;
	font-size: 12px;
	line-height: 1.428571429;
	border-radius: 15px;
	background-color: #fff;
	opacity: 1!important;
}
.btn.btn-circle {
	margin-top: 0;
}
.active-step {
	transform: scale(1.25);
}
</style>

<div class="text-center">

	<h2>{{ trans('sanatorium/orders::cart.title') }}</h2>

	<h5>{{ trans('sanatorium/orders::cart.subtitle') }}</h5>

</div>

<div class="stepwizard">
	<div class="stepwizard-row">
		<?php $index = 1; ?>
		@for ( $i = 1; $i < 5; $i++ )
			@if ( $i == 2 && config('sanatorium-orders.ff_delivery_payment') )
				{{-- Fast forward delivery & payment --}}
			@else
				<div class="stepwizard-step">
					<a class="btn btn-default btn-circle {{ ($step == $i ? 'active-step' : null) }}" href="#">{{ $index }}</a>
					<p>{{ trans('sanatorium/orders::cart.steps.' . $i) }}</p>
				</div>
				<?php $index++; ?>
			@endif
		@endfor
	</div>
</div>