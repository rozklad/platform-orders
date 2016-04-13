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

	<?php $step = 4; ?>
	@include('sanatorium/orders::cart/steps/heading')

	@include('sanatorium/orders::cart/partials/summary')

	@include('sanatorium/orders::cart/partials/totals')
	
	<form method="POST">	
		<button type="submit" class="btn btn-primary btn-inverse btn-lg btn-block" href="{{ route('sanatorium.orders.cart.confirmed') }}">

			{{ trans('sanatorium/orders::cart.proceed.confirmed') }}

		</button>
	</form>

@stop