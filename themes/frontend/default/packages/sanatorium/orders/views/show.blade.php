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
	<h3>{{ trans('sanatorium/orders::cart.single.created_at', ['created_at' => $order->created_at->format('j.n.Y') ]) }}</h3>

	<hr>

	{{ Cart::unserialize($order->cart) }}

	<?php $items = Cart::items() ?>
	
	<div class="row">

		<div class="col-sm-6">
			<h4>{{ trans('sanatorium/orders::cart.billing.title') }}</h4>
			<?php $address = Sanatorium\Addresses\Models\Address::find($order->address_billing_id) ?>
			<p>{{ $address->name }}</p>
			<p>{{ $address->street }}</p>
			<p>{{ $address->city }}</p>
			<p>{{ $address->postcode }}</p>
			<p>{{ $address->country }}</p>
			
			<p>{{ $address->ic }}</p>
			<p>{{ $address->dic }}</p>

		</div>
		<div class="col-sm-6">
			<h4>{{ trans('sanatorium/orders::cart.delivery.title') }}</h4>
			<?php $address = Sanatorium\Addresses\Models\Address::find($order->address_delivery_id) ?>
			<p>{{ $address->name }}</p>
			<p>{{ $address->street }}</p>
			<p>{{ $address->city }}</p>
			<p>{{ $address->postcode }}</p>
			<p>{{ $address->country }}</p>
		</div>

	</div>

	<hr>

	<div class="row">
		
		<div class="col-sm-6">
			<h4>{{ trans('sanatorium/orders::cart.totals.delivery') }}</h4>
			<?php $deliverytype = Sanatorium\Orders\Models\Deliverytype::find($order->delivery_type_id) ?>
			<p>{{ $deliverytype->delivery_title }}</p>
			{!! $deliverytype->delivery_description !!}
			<p>{{ $deliverytype->price_vat }}</p>
		</div>

	</div>

	<hr>

	<h2>{{ trans('sanatorium/orders::cart.title') }}</h2>

	<table class="table">

		<tbody>

	@foreach( $items as $item )
		<?php $product = Product::find($item->get('id')); ?>
		<tr class="product-row" data-id="{{ $item->get('id') }}" data-rowid="{{ $item->get('rowId') }}">
			<td class="text-center">
				@if ( $product->has_cover_image )
					<a href="{{ $product->url }}" target="_blank">
						<img src="{{ $product->coverThumb(60,60) }}" alt="{{ $product->product_title }}" width="60" height="60">
					</a>
				@else
					{{ $item->get('id') }}
				@endif
			</td>
			<td class="col-xs-4">
				<a href="{{ $product->url }}" target="_blank">{{ $product->product_title }}</a> 
				@if ( $product->code )
					<span class="text-muted">({{ $product->code }})</span>
				@endif

				@if ( $order->status->id == config('sanatorium-reviews.finished_status') )
					@hook('product.bought', ['product' => $product])
				@endif
			</td>
			<td>
				{{ $item->get('quantity') }} ks
			</td>
			<td class="text-right">
				{{-- Price one --}}
				{{ $product->getPrice('vat', 1) }}
			</td>
			<td class="text-right">
				<span class="total-price-item" data-price-type="vat_quantity">
					{{-- Price item quantity --}}
					{{ $product->getPrice('vat', $item->quantity()) }}
				</span>
			</td>
		</tr>
	@endforeach

		</tbody>

	</table>

	<hr>

	<span style="font-size:21px;">{{ trans('sanatorium/orders::cart.data.total') }} {{ $order->price_vat }}</span>

	</div>

	</div>

@endif


@stop