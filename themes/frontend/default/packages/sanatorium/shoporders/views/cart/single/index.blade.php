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

	<form action="{{ route('sanatorium.orders.cart.place') }}">

	@include('sanatorium/orders::cart/partials/summary')
	
	@if ( count($items) )

		@include('sanatorium/orders::cart/partials/delivery_payment')

		@include('sanatorium/orders::cart/partials/user')

		@include('sanatorium/orders::cart/partials/totals')

		@include('sanatorium/orders::cart/partials/actions')

	@else

	@endif

	</form>

@stop