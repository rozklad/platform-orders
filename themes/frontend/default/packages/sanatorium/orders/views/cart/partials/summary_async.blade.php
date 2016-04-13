@section('styles')
@parent
@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){
	$('[data-change-quantity]').unbind('click').click(function(event){
		event.preventDefault();

		var $quantity_input = $(this).parents('.table-quantity').find('[name="quantity"]'),
			current_value = parseInt( $quantity_input.val(), 10 ),
			change = parseInt( $(this).data('change-quantity'), 10 );

		$quantity_input.val( parseInt( current_value + (change), 10) );

		$quantity_input.trigger('change');

		return false;
	});
});
</script>
@stop
	
	<div class="panel panel-default cart-panel cart-part">

		@if ( count($items) )
		<table class="table table-noborder" data-token="{{ csrf_token() }}">
			<thead>
				<tr>
					<th width="60" class="summary-col-thumb">
						
					</th>
					<th class="summary-col-name">
						{{ trans('sanatorium/orders::cart.summary.name') }}
					</th>
					<th class="text-right summary-col-price_single_vat">
						{{ trans('sanatorium/orders::cart.summary.price_single_vat') }}
					</th>
					<th class="text-right summary-col-price_vat">
						{{ trans('sanatorium/orders::cart.summary.price_vat') }}
					</th>
					@if (isset($activeSummary))
					<th class="summary-col-actions">

					</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@foreach( $items as $item )
				<?php $product = Product::find($item->get('id')); ?>
				@if ( is_object($product) )
				<tr class="product-row" data-id="{{ $item->get('id') }}" data-rowid="{{ $item->get('rowId') }}">
					<td class="text-center summary-col-thumb">
						@if ( $product->has_cover_image )
							<a href="{{ $product->url }}" target="_blank">
								<img src="{{ $product->coverThumb(60,60) }}" alt="{{ $product->product_title }}" width="60" height="60">
							</a>
						@else
							{{ $item->get('id') }}
						@endif
					</td>
					<td class="col-xs-4 summary-col-name">
						
						<a href="{{ $product->url }}" target="_blank">{{ $product->product_title }}</a> 
						
						@if ($product->code)
							<span class="text-muted">({{ $product->code }})</span>
						@endif
						
						{{-- Change quantity --}}
						@if ( isset($activeSummary) )
							@if ( isset($compatibleQuantity) ) 
								<table class="table table-quantity" style="margin-bottom:0;">
									<tbody>
										<tr>
											<td>
												<button type="button" class="btn btn-default" data-change-quantity="-1"><i class="fa fa-minus"></i></button>
											</td>
											<td>
												<input type="text" name="quantity" value="{{ $item->quantity() }}">
											</td>
											<td>
												<button type="button" class="btn btn-default" data-change-quantity="1"><i class="fa fa-plus"></i></button>
											</td>
										</tr>
									</tbody>
								</table>
							@else 
								<input type="text" name="quantity" value="{{ $item->quantity() }}">
							@endif
						@else
							{{ $item->quantity() }}
						@endif
					</td>
					<td class="text-right summary-col-price_single_vat">
						{{-- Price one --}}
						{{ $product->getPrice('vat', 1) }}
					</td>
					<td class="text-right  summary-col-price_vat">
						<span class="total-price-item" data-price-type="vat_quantity">
							{{-- Price item quantity --}}
							{{ $product->getPrice('vat', $item->quantity()) }}
						</span>
					</td>
					@if (isset($activeSummary))
					<td class="summary-col-actions">
						<a href="#remove" class="cart-remove">
							<i class="fa fa-trash"></i>
						</a>
					</td>
					@endif
				</tr>
				@endif
				
			@endforeach
			
			{{-- Show extra items --}}
			@hook('cart.summary.items.async')

			</tbody>
		</table>

		<div class="panel-footer">

			<a href="{{ route('sanatorium.orders.cart.clear') }}">{{ trans('sanatorium/orders::cart.actions.clear') }}</a>

		</div>
		@else
			
			<p class="alert alert-info">{{ trans('sanatorium/orders::cart.messages.empty') }}</p>

		@endif

 	</div>
 