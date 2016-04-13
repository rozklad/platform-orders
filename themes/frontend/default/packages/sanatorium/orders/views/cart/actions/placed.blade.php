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

{{-- Page content --}}
@section('page')

	<p class="text-center">
		{{ trans('sanatorium/orders::cart.placed.thank_you') }}
		<br>
		<a href="/">{{ trans('sanatorium/orders::cart.placed.return') }}</a>
	</p>

	<br>
	@if ( $payment_online )
		@if ( $payment_success )
			<p class="alert alert-success text-center">
				{{ trans('sanatorium/orders::cart.placed.messages.payments.success') }}
			</p>
		@else
			<p class="alert alert-danger text-center">
				{{ trans('sanatorium/orders::cart.placed.messages.payments.error') }}
			</p>
		@endif
	@endif

	@hook('order.placed.thanks', ['order' => $order, 'payment_online' => $payment_online, 'payment_success' => $payment_success])

@stop