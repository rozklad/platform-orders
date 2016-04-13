@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{ trans('sanatorium/orders::cart.title') }}
@stop

{{-- Meta description --}}
@section('meta-description')
{{ trans('sanatorium/orders::cart.title') }}
@stop

{{-- Cart urls --}}
@section('scripts')
@parent
<script type="text/javascript">
var cart_url = {
	'delete': '{{ route('sanatorium.orders.cart.delete') }}',
	'update': '{{ route('sanatorium.orders.cart.update') }}',
	'prices': '{{ route('sanatorium.orders.cart.prices') }}'
};
</script>
@stop

{{-- Cart script --}}
{{ Asset::queue('cart', 'sanatorium/orders::js/cart.js', 'jquery') }}

{{-- Page content --}}
@section('page')

	<?php $step = 3; ?>
	@include('sanatorium/orders::cart/steps/heading')

	<form action="{{ route('sanatorium.orders.cart.place') }}" method="POST">
	
	@if ( count($items) )

		@include('sanatorium/orders::cart/partials/delivery_payment')

	@else

	@endif

		@include('sanatorium/orders::cart/partials/handover')
		
		@if ( true )
		<button class="btn btn-primary btn-inverse btn-lg btn-block" type="submit">

			{{ trans('sanatorium/orders::cart.proceed.confirmation') }}

		</button>
		@else
		<button class="btn btn-primary btn-inverse btn-lg btn-block" type="submit">

			{{ trans('sanatorium/orders::cart.proceed.user') }}

		</button>
		@endif

	</form>

@stop