
		<?php $items = Cart::items(); ?>

		@if ( count($items) )
		<table class="table table-noborder cart-summary-lite" data-token="{{ csrf_token() }}">
			<tbody>
			@foreach( $items as $item )
				<?php $product = Product::find($item->get('id')); ?>
				@if ( is_object($product) )
				<tr class="product-row" data-id="{{ $item->get('id') }}" data-rowid="{{ $item->get('rowId') }}">
					<td class="text-center">
						@if ( $product->has_cover_image )
							<a href="{{ $product->url }}" target="_blank">
								<img src="{{ $product->coverThumb(60,60) }}" alt="{{ $product->product_title }}" width="60"  height="60">
							</a>
						@else
							{{ $item->get('id') }}
						@endif
					</td>
					<td>
						<a href="{{ $product->url }}" target="_blank">{{ $product->product_title }}</a>
						<br>
						{{ $product->getPrice('vat', $item->quantity()) }}
					</td>
					<td>
						<a href="#remove" class="cart-remove">
							<i class="fa fa-trash"></i>
						</a>
					</td>
				</tr>

				{{-- Show extra items --}}
				@hook('cart.summary.items.lite')
				
				@endif
			@endforeach
			</tbody>
		</table>
		@else

			<p class="alert alert-info">{{ trans('sanatorium/orders::cart.messages.empty') }}</p>

		@endif
