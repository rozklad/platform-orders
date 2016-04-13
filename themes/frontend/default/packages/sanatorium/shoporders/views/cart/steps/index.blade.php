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

	<?php $step = 1; ?>
	@include('sanatorium/orders::cart/steps/heading')

	@include('sanatorium/orders::cart/partials/summary')
	
	@if ( count($items) )
		@if ( config('sanatorium-orders.ff_delivery_payment') )
			<a class="btn btn-primary btn-inverse btn-lg btn-block" href="{{ route('sanatorium.orders.cart.user') }}">

				{{ trans('sanatorium/orders::cart.proceed.user') }}

			</a>
		@else
			@if ( true )
			<a class="btn btn-primary btn-inverse btn-lg btn-block" href="{{ route('sanatorium.orders.cart.user') }}">

				{{ trans('sanatorium/orders::cart.proceed.user') }}

			</a>
			@else
			<a class="btn btn-primary btn-inverse btn-lg btn-block" href="{{ route('sanatorium.orders.cart.delivery') }}">

				{{ trans('sanatorium/orders::cart.proceed.delivery') }}

			</a>
			@endif
		@endif
	@endif

@stop