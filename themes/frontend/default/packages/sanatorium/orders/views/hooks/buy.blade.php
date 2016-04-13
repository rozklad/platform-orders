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

<form method="POST" action="{{ route('sanatorium.orders.cart.add') }}" class="form-inline form-to-cart" role="form">
	<input type="hidden" name="id" value="{{ $object->id }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<div class="input-group">
		<input class="form-control quantity" name="quantity" type="number" value="1" min="1" required>
		<div class="input-group-btn">
			<button class="btn btn-success btn-add-to-cart" type="submit">
				<i class="fa fa-cart-plus"></i>
			</button>
		</div>
	</div>
</form>