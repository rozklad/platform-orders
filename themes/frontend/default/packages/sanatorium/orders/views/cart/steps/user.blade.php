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

	<?php $step = 2; ?>
	@include('sanatorium/orders::cart/steps/heading')

	<form action="{{ route('sanatorium.orders.cart.delivery') }}" method="POST">

		@if ( count($items) )

			@include('sanatorium/orders::cart/partials/user')

		@else

		@endif

		<div class="form-group">

			@include('sanatorium/orders::cart/partials/handover')
			
			@if ( true )
			<button type="submit" class="btn btn-primary btn-inverse btn-lg btn-block">

				{{ trans('sanatorium/orders::cart.proceed.delivery') }}

			</button>
			@else
			<button type="submit" class="btn btn-primary btn-inverse btn-lg btn-block">

				{{ trans('sanatorium/orders::cart.proceed.confirmation') }}

			</button>
			@endif

		</div>

	</form>

@stop