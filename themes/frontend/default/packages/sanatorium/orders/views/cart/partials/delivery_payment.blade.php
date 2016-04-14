@section('styles')
@parent
<style type="text/css">
.disabled {
	opacity: 0.4;
}
.page-wrapper .delivery-cost {
	display: none;
}
</style>
@stop

@section('scripts')
@parent
<script type="text/javascript">
var delivery_to_payments = {
	@foreach( \Sanatorium\Orders\Models\DeliveryType::all() as $deliverytype)
	{{ $deliverytype->id }} : [{{ implode(',', $deliverytype->paymenttypes()->select('shop_payment_types.id')->lists('shop_payment_types.id')->toArray()) }}],
	@endforeach
};

function disableAllPayments() {
	$('[name="paymenttype"]').each(function(){
		$(this).attr('disabled', 'disabled').prop('checked', false).parents('div:first').addClass('disabled');
	});
}

function enablePayment($el) {
	$el.attr('disabled', false).parents('div:first').removeClass('disabled');
}

function selectDelivery() {
	var selected_delivery = $('[name="deliverytype"]:checked').val(),
		available_payments = delivery_to_payments[selected_delivery];
		
	disableAllPayments();

	for ( var key in available_payments ) {
		enablePayment( $('[name="paymenttype"][value="'+available_payments[key]+'"]') );
	}
}

function preselectPaymentDelivery() {
	if ( $('[name="deliverytype"]').length == 1 ) {
		$('[name="deliverytype"]').trigger('click');
	}
	if ( $('[name="paymenttype"]').length == 1 ) {
		$('[name="paymenttype"]').trigger('click');
	}
}

$(function(){

	disableAllPayments();

	selectDelivery();

	$('.delivery-payment-input').change(function(){
		selectDelivery();
	});

	preselectPaymentDelivery();
});
</script>
@stop

	<div class="delivery-cost">
	@if ( $prices['total'] < 5000 )
	<div class="alert alert-info">
		<p>{{ trans('sanatorium/orders::cart.alerts.delivery_not_free', ['from' => '5000 Kč']) }}</p>
		@if ( $prices['total'] > 3000 )
			<p>{!! trans('sanatorium/orders::cart.alerts.delivery_free_remains', ['remains' => Converter::to('currency.'.$currency->code)->value(5000 - $prices['total'])->format($currency->short_format)]) !!}</p>
		@endif
	</div>
	@else
	<div class="alert alert-success">
		<p>{{ trans('sanatorium/orders::cart.alerts.delivery_free', ['from' => '5000 Kč']) }}</p>
	</div>
	@endif
	</div>

	<div class="panel panel-default cart-panel cart-part">
		
		<header class="panel-heading">
			
			{{ trans('sanatorium/orders::cart.steps.2') }}
		
		</header>
		
		<div class="panel-body">
			
			<div class="row">

				<div class="col-sm-6">

					{{-- Deliveries --}}
					@foreach( \Sanatorium\Orders\Models\DeliveryType::where('money_min', '<=', $prices['total'])->where(function($q) use ($prices) {
						$q->where('money_max', '>=', $prices['total'])
							->orWhere('money_max', 0);
					})->get() as $deliverytype)
					<div class="checkbox">

						<label for="deliverytype-{{ $deliverytype->id }}">

							<input type="radio" class="delivery-payment-input" id="deliverytype-{{ $deliverytype->id }}" value="{{ $deliverytype->id }}" name="deliverytype" 
								@if ( $selected = Cart::getMetaData('deliverytype') ) 
									@if ( $selected->id == $deliverytype->id ) 
										checked 
									@endif 
								@endif >
							
							<h5>
								{{ $deliverytype->delivery_title }}
								@if ( !$deliverytype->free )
									@if ( isset($shipping_multiplier) ) 
									- <strong>{{ Sanatorium\Pricing\Models\Currency::format( (int)$deliverytype->price_vat*$shipping_multiplier ) }}</strong>
									@else
									- <strong>{{ $deliverytype->price_vat }}</strong>
									@endif
								@else
									- {{ trans('sanatorium/orders::cart.free') }}
								@endif
							</h5>

							{!! $deliverytype->delivery_description !!}

						</label>

					</div>
					@endforeach

				</div>

				<div class="col-sm-6">

					{{-- Payments --}}
					@foreach( \Sanatorium\Orders\Models\PaymentType::all() as $paymenttype)
					<div class="checkbox">

						<label for="paymenttype-{{ $paymenttype->id }}">

							<input type="radio" id="paymenttype-{{ $paymenttype->id }}" value="{{ $paymenttype->id }}" name="paymenttype"
							@if ( $selected = Cart::getMetaData('paymenttype') ) 
								@if ( $selected->id == $paymenttype->id ) 
									checked 
								@endif 
							@endif>

							<h5>{{ $paymenttype->payment_title }}</h5>

							{!! $paymenttype->payment_description !!}

						</label>

					</div>
					@endforeach

				</div>

			</div>

		</div>

	</div>