@extends('layouts/default')

@section('page')

@if ( $order->exists )

	<div class="tab-panel">

	<div class="tab-content">

	<a href="{{ route('user.orders') }}" class="text-bigger">
		<i class="ion-ios-arrow-thin-left"></i> {{ trans('sanatorium/orders::orders/common.actions.back') }}
	</a>
	
	<br>

	<h1>{{ trans('sanatorium/orders::cart.single.title', ['id' => $order->public_id]) }}</h1>

	<table class="table">

		<tbody>

			<tr>

				<td>{{ trans('sanatorium/orders::orders/common.tracking.placed') }}</td>
				<td>{{ $order->created_at->format('j.n.Y H:i') }}</td>

			</tr>

			@foreach( $order->statuses()->withPivot('created_at')->get() as $status )
				
				<tr>

					<td>{{ trans('sanatorium/orders::orders/common.tracking.status', ['status' => $status->name]) }}</td>
					<td>{{ $status->pivot->created_at->format('j.n.Y H:i') }}</td>

				</tr>

			@endforeach
			
		</tbody>
	
	</table>

	</div>

	</div>

@endif


@stop