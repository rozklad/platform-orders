@section('styles')
@parent
<style type="text/css">
.table.table-totals {
	
}
.table.table-totals tbody tr td {
	border-top: 0;
}
.table.table-totals tr.row-total {
	font-size: 18px;
	font-weight: 700;
}
</style>
@stop

@section('scripts')
@parent
<script type="text/javascript">

</script>
@stop

	<div class="panel panel-default cart-panel cart-part">
		
		<header class="panel-heading">
			
			{{ trans('sanatorium/orders::cart.totals.title') }}
		
		</header>
		
		<div class="panel-body">
			
			<table class="table table-totals">
				<tbody>
					@if ( is_object($currency) )
					<tr class="row-subtotal">
						<td class="text-right">{{ trans('sanatorium/orders::cart.data.subtotal') }}</td>
						<td data-total-price>{{ Converter::to('currency.'.$currency->code)->value($prices['subtotal'])->format($currency->short_format) }}</td>
					</tr>
					@endif
					@if ( isset($order) )
						@if ( $deliverytype = $order->deliverytype )
						<tr class="row-delivery">
							<td class="text-right">{{ trans('sanatorium/orders::cart.data.delivery_price') }}</td>
							@if ( Cart::getMetaData('shipping_multiplier')  ) 
							<td data-delivery-price>{{ Sanatorium\Pricing\Models\Currency::format( (int)$deliverytype->price_vat*Cart::getMetaData('shipping_multiplier') ) }}</td>
							@else
							<td data-delivery-price>{{ $deliverytype->price_vat }}</td>
							@endif
						</tr>
						@endif
					@else
						@if ( $deliverytype = Cart::getMetaData('deliverytype') )
						<tr class="row-delivery">
							<td class="text-right">{{ trans('sanatorium/orders::cart.data.delivery_price') }}</td>
							@if ( Cart::getMetaData('shipping_multiplier')  ) 
							<td data-delivery-price>{{ Sanatorium\Pricing\Models\Currency::format( (int)$deliverytype->price_vat*Cart::getMetaData('shipping_multiplier') ) }}</td>
							@else
							<td data-delivery-price>{{ $deliverytype->price_vat }}</td>
							@endif
						</tr>
						@endif
					@endif
					@if ( is_object($currency) )
					<tr class="row-total">
						<td class="text-right">{{ trans('sanatorium/orders::cart.data.total') }}</td>
						<td data-products-price>{{ Converter::to('currency.'.$currency->code)->value(Cart::total())->format($currency->short_format) }}</td>
					</tr>
					@endif
				</tbody>
			</table>

		</div>

	</div>