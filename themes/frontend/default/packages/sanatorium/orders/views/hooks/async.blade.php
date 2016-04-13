
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
{{ Asset::queue('async-cart-js', 'sanatorium/orders::js/async.js', 'jquery') }}


<div class="async-cart-overlay" data-async-cart-trigger>&nbsp;</div>

<div class="async-cart" id="async-cart">

	<div class="panel">
		
		<div class="panel-heading">

			<h3 class="panel-title">

				{{ trans('sanatorium/orders::cart.title') }}
				
				<a href="#" data-async-cart-trigger class="async-cart-close pull-right">
		
					<i class="ion-ios-close-empty"></i>

				</a>

			</h3>
		
		</div>
		
		<div class="panel-body">
			
			<?php $activeSummary = true; ?>
			<?php $compatibleQuantity = true; ?>
			@include('sanatorium/orders::cart/partials/summary_async')

		</div>

		<div class="panel-footer">

			@if ( isset($subtotal) )
				<table class="table table-noborder async-row-table" data-token="{{ csrf_token() }}">
					<tbody>
						<tr>
							<td class="col-xs-8">
								{{ trans('sanatorium/orders::cart.data.total') }}
							</td>
							<td class="col-xs-4 text-right" data-total-price>
								@if ( $total < 0 )
									{{ Converter::to('currency.'.$currency->code)->value(0)->format($currency->short_format) }}
								@else
									{{ Converter::to('currency.'.$currency->code)->value($total)->format($currency->short_format) }}
								@endif
							</td>
						</tr>
					</tbody>
				</table>
			@endif

			@if ( count($items) )
			<a class="btn btn-primary btn-inverse btn-lg btn-block" href="{{ route('sanatorium.orders.cart.index') }}">

				{{ trans('sanatorium/orders::cart.proceed.checkout') }}

			</a>
			@endif
		</div>

	</div>

</div>