@section('styles')
@parent
<style type="text/css">
</style>
@stop

@section('scripts')
@parent
<script type="text/javascript">

var delivery_original = [],
	initial_address_load = true;

function switchDeliveryIsSame($checkbox) {

	if ( $checkbox.is(':checked') ) {

		// Fill delivery with billing details
		$('[name^="address[fakturacni]"]').each(function(){

			var name = $(this).attr('name'),
				name = name.replace('address[fakturacni][', '').replace(']', '');;

			delivery_original[name] = $('[name="address[dodaci]['+name+']"]').val();
			
			$('[name="address[dodaci]['+name+']"]').val($(this).val());

		});

	} else {

		// Do not clean up values on initial load
		if ( initial_address_load ) {
			initial_address_load = false;
			return ;
		}

		// Return billing details to original values
		$('[name^="address[dodaci]"]').each(function(){

			var name = $(this).attr('name'),
				name = name.replace('address[dodaci][', '').replace(']', '');
			
			$('[name="address[dodaci]['+name+']"]').val(delivery_original[name]);

		});

	}

}

$(function(){
	
	$('#sameBillingAsDelivery').change(function(event){

		switchDeliveryIsSame( $(this) );

	});

	// Init on load
	switchDeliveryIsSame( $('#sameBillingAsDelivery') );

});
</script>
@stop

	<div class="panel panel-default cart-panel cart-part">

		<div id="personal_info" class="panel-collapse collapse in" role="tabpanel">
			
			<div class="panel-body">
				
				<div class="form-group">
					<div class="col-sm-4">
						<label for="order_email" class="control-label">{{ trans('sanatorium/orders::cart.personal.email') }}</label>
					</div>
					<div class="col-sm-8">
						@if ( isset($currentUser) )
						<input type="email" class="form-control" name="order_email" id="order_email" value="{{ $currentUser->email }}" required>
						@else
						<input type="email" class="form-control" name="order_email" id="order_email" required>
						@endif
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-4">
						<label for="order_phone" class="control-label">{{ trans('sanatorium/orders::cart.personal.phone') }} <small>{{ trans('sanatorium/orders::cart.optional') }}</small></label>
					</div>
					<div class="col-sm-8">
						<input type="tel" class="form-control" name="order_phone" id="order_phone">
					</div>
				</div>

			</div>

		</div>

	</div>
	
	<div class="panel panel-default cart-panel cart-part">
		<div class="panel-heading" role="tab" id="fakturacni-header">
			<h4 class="panel-title">
				<a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#fakturacni-udaje" aria-expanded="true" aria-controls="fakturacni-udaje">
					{{ trans('sanatorium/orders::cart.billing.title') }}
				</a>
			</h4>
		</div>

		<div id="fakturacni-udaje" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="fakturacni-header">
			<div class="panel-body">

				<input type="hidden" name="address[fakturacni][label]" value="Fakturační adresa">

				<div class="form-group">
					<div class="col-sm-4">
						<label for="name" class="control-label">{{ trans('sanatorium/orders::cart.billing.name') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['fakturacni']->name }}" name="address[fakturacni][name]" id="name">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="street" class="control-label">{{ trans('sanatorium/orders::cart.billing.street') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['fakturacni']->street }}" name="address[fakturacni][street]" id="street">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="city" class="control-label">{{ trans('sanatorium/orders::cart.billing.city') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['fakturacni']->city }}" name="address[fakturacni][city]" id="city">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="postcode" class="control-label">{{ trans('sanatorium/orders::cart.billing.zip') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['fakturacni']->postcode }}" name="address[fakturacni][postcode]" id="postcode">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="country" class="control-label">{{ trans('sanatorium/orders::cart.billing.country') }}</label>
					</div>
					<div class="col-sm-8">
						@if ( isset($deliveryCountries) )
							<select name="address[fakturacni][country]" id="country" class="form-control">
								@foreach( $deliveryCountries as $country )
									@if ( isset($suggestedCountries) )
										<option value="{{ $country->name_simple }}" {{ $suggestedCountries['fakturacni'] == $country->name_simple ? 'selected' : '' }}>{{ $country->name_simple }}</option>
									@else
										<option value="{{ $country->name_simple }}" {{ $primaryAddresses['fakturacni']->country == $country->name_simple ? 'selected' : '' }}>{{ $country->name_simple }}</option>
									@endif
								@endforeach
							</select>
						@else
							<select name="address[fakturacni][country]" id="country" class="form-control">
								@if ( isset($suggestedCountries) )
								<option value="Česká republika" {{ $suggestedCountries['fakturacni'] == 'Česká republika' ? 'selected' : '' }}>Česká republika</option>
								<option value="Slovensko"  {{ $suggestedCountries['fakturacni'] == 'Slovensko' ? 'selected' : '' }}>Slovensko</option>
								@else
								<option value="Česká republika" {{ $primaryAddresses['fakturacni']->country == 'Česká republika' ? 'selected' : '' }}>Česká republika</option>
								<option value="Slovensko"  {{ $primaryAddresses['fakturacni']->country == 'Slovensko' ? 'selected' : '' }}>Slovensko</option>
								@endif
							</select>
						@endif
					</div>
				</div>
				
			</div>
		</div>
	 </div>

	 <label class="alert alert-info" for="sameBillingAsDelivery" style="width:100%">
	 	<input type="checkbox" id="sameBillingAsDelivery"> {{ trans('sanatorium/orders::cart.same_billing_as_delivery') }}
	 </label>

	 <div class="panel panel-default cart-panel cart-part">
		<div class="panel-heading" role="tab" id="dodaci-header">
			<h4 class="panel-title">
				<a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#dodaci-udaje" aria-expanded="true" aria-controls="dodaci-udaje">
					{{ trans('sanatorium/orders::cart.delivery.title') }}
				</a>
			</h4>
		</div>
		<div id="dodaci-udaje" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="dodaci-header">
			<div class="panel-body">
				
				<input type="hidden" name="address[dodaci][label]" value="Dodací adresa">

				<div class="form-group">
					<div class="col-sm-4">
						<label for="dodaci-name" class="control-label">{{ trans('sanatorium/orders::cart.delivery.name') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['dodaci']->name }}" name="address[dodaci][name]" id="dodaci-name">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="dodaci-street" class="control-label">{{ trans('sanatorium/orders::cart.delivery.street') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['dodaci']->street }}" name="address[dodaci][street]" id="dodaci-street">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="dodaci-city" class="control-label">{{ trans('sanatorium/orders::cart.delivery.city') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['dodaci']->city }}" name="address[dodaci][city]" id="dodaci-city">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="dodaci-postcode" class="control-label">{{ trans('sanatorium/orders::cart.delivery.zip') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['dodaci']->postcode }}" name="address[dodaci][postcode]" id="dodaci-postcode">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="dodaci-country" class="control-label">{{ trans('sanatorium/orders::cart.delivery.country') }}</label>
					</div>
					<div class="col-sm-8">
						@if ( isset($deliveryCountries) )
							<select name="address[dodaci][country]" id="country" class="form-control">
								@foreach( $deliveryCountries as $country )
									@if ( isset($suggestedCountries) )
										<option value="{{ $country->name_simple }}" {{ $suggestedCountries['dodaci'] == $country->name_simple ? 'selected' : '' }}>{{ $country->name_simple }}</option>
									@else
										<option value="{{ $country->name_simple }}" {{ $primaryAddresses['dodaci']->country == $country->name_simple ? 'selected' : '' }}>{{ $country->name_simple }}</option>
									@endif
								@endforeach
							</select>
						@else
							<select name="address[dodaci][country]" id="country" class="form-control">
								@if ( isset($suggestedCountries) )
								<option value="Česká republika" {{ $suggestedCountries['dodaci'] == 'Česká republika' ? 'selected' : '' }}>Česká republika</option>
								<option value="Slovensko"  {{ $suggestedCountries['dodaci'] == 'Slovensko' ? 'selected' : '' }}>Slovensko</option>
								@else
								<option value="Česká republika" {{ $primaryAddresses['dodaci']->country == 'Česká republika' ? 'selected' : '' }}>Česká republika</option>
								<option value="Slovensko"  {{ $primaryAddresses['dodaci']->country == 'Slovensko' ? 'selected' : '' }}>Slovensko</option>
								@endif
							</select>
						@endif
					</div>
				</div>
			</div>
		</div>
	 </div>

	
	{{-- Company information --}}
	 <div class="panel panel-default cart-panel cart-part">
		<div class="panel-heading" role="tab" id="firemni-header">
			<h4 class="panel-title">
				<a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#firemni-udaje" aria-expanded="true" aria-controls="firemni-udaje">
					{{ trans('sanatorium/orders::cart.company.title') }}
				</a>
			</h4>
		</div>
		<div id="firemni-udaje" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="firemni-header">
			<div class="panel-body">
				<div class="form-group">
					<div class="col-sm-4">
						<label for="ic" class="control-label">{{ trans('sanatorium/orders::cart.company.ic') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['fakturacni']->ic }}" name="address[fakturacni][ic]" id="ic">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<label for="dic" class="control-label">{{ trans('sanatorium/orders::cart.company.dic') }}</label>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" value="{{ $primaryAddresses['fakturacni']->dic }}" name="address[fakturacni][dic]" id="dic">
					</div>
				</div>
			</div>
		</div>
	 </div>

	{{-- Order note --}}
	<div class="panel panel-default cart-panel cart-part">
		<div class="panel-heading" role="tab" id="order-note-header">
			<h4 class="panel-title">
				<a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#order-note" aria-expanded="true" aria-controls="order-note">
					{{ trans('sanatorium/orders::cart.order_note') }}
				</a>
			</h4>
		</div>
		<div id="order-note" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="order-note-header">
			<div class="panel-body">
				<div class="form-group">
					<div class="col-sm-4">
						<label for="order_note" class="control-label">{{ trans('sanatorium/orders::cart.order_note') }}</label>
					</div>
					<div class="col-sm-8">
						<textarea class="form-control" name="order_note" id="order_note"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>