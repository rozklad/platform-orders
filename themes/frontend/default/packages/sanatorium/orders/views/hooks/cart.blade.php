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


@section('styles')
@parent
<style type="text/css">
.dropdown-cart-area {
	position: relative;
}
.dropdown-cart.open {
	display: block;
	width: 100%;
} 
.dropdown-cart.dropdown-menu {
	padding-top: 0;
	padding-bottom: 0;
	font-size: 12px;
}
</style>
@stop

{{-- Cart script --}}
{{ Asset::queue('cart', 'sanatorium/orders::js/cart.js', 'jquery') }}

<div class="{{ $class }} dropdown-cart-area hidden-xs {{ $quantity > 0 ? 'not-empty' : 'empty' }}">
	<a class="btn btn-default btn-cart-dropdown dropdown-toggle" href="#" data-target="#dropdown-cart" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="fa fa-shopping-cart"></i>
		<span class="caret"></span>
		<span class="badge">{{ $quantity }}</span>
		<span class="cart-title">{{ trans('sanatorium/orders::cart.title') }}</span>
	</a>
	<div class="dropdown-cart dropdown-menu panel panel-default" id="dropdown-cart">
		@include('sanatorium/orders::cart/partials/summary_lite')
		<div class="panel-footer">
			<div class="row">
				<div class="col-xs-6">
					<a class="btn btn-link btn-sm btn-cart-clear" href="{{ route('sanatorium.orders.cart.clear') }}">
						{{ trans('sanatorium/orders::cart.actions.clear') }}
					</a>
				</div>
				<div class="col-xs-6">
					<a class="btn btn-success btn-sm pull-right btn-cart-show" href="{{ route('sanatorium.orders.cart.index') }}">
						{{ trans('sanatorium/orders::cart.title') }}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="{{ $class }} mobile-cart-area visible-xs">
	<a class="btn btn-success btn-cart-show" href="{{ route('sanatorium.orders.cart.index') }}">
		{{ trans('sanatorium/orders::cart.title') }}
	</a>
</div>
