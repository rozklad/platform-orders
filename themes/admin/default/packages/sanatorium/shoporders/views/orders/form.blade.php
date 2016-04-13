@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/orders::orders/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
<script type="text/javascript">
$(function(){
	$('.order-action').click(function(){
		var order_id = $(this).data('id'),
			action = $(this).data('action'),
			data = {};

		data['order_id'] = order_id;
		data['action'] = action;

		if ( action == 'refund' ) {
			data['amount'] = prompt("How much to refund?");
		}

		if ( action == 'close' ) {
			data['amount'] = prompt("What is the final price?");
		}

		$.ajax({
			url: '{{ route('admin.sanatorium.orders.orders.action') }}',
			method: 'POST',
			data: data
		}).done(function(msg){
			// order status changed
    		$('body').pgNotification({
    			'style' : 'bar',
    			'position' : 'top',
    			'message' : msg.msg,
    			'type' : 'alert alert-' + msg.type,
    			'showClose' : false
    		}).show();

    		setTimeout(function(){
				location.reload();
    		}, 1500);
		});
	});
});
</script>
@stop

{{-- Inline styles --}}
@section('styles')
@parent
<style type="text/css">
.scaffold-components {
  margin-top:32px;
  overflow: hidden;
}
.scaffold-components-list {
  padding-left: 0;
  list-style: none;
}
.scaffold-components li {
  float: right;
  width: 100px;
  height: 80px;
  padding: 10px;
  font-size: 10px;
  line-height: 1.4;
  text-align: center;
  border: 1px solid #ddd;
  margin:2px;
}
.scaffold-components .fa {
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 24px;
}
.scaffold-components li span {
  display: block;
  text-align: center;
  font-size:9px;
}
.scaffold-components li:hover {
  background-color: #f7f7f7;
  cursor: pointer;
}

/* icons */
.icon-reverse:before {
	content: "\f112";
}
.icon-refund:before {
	content: "\f0d6";
}
.icon-close:before {
	content: "\f05c";
}

</style>
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="orders-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.orders.orders.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $order->exists ? $order->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($order->exists)
							<li>
								{{-- @todo: make extendable --}}
								<a href="{{ route('admin.sanatorium.orders.orders.export.cpost', $order->id) }}" data-toggle="tooltip" data-original-title="Export Česká pošta">
									<i class="fa fa-truck"></i><span class="visible-xs-inline">Česká pošta</span>
								</a>
							</li>

							<li>
								{{-- @todo: make extendable --}}
								<a href="{{ route('admin.sanatorium.orders.orders.export.gls', $order->id) }}" data-toggle="tooltip" data-original-title="Export GLS">
									<i class="fa fa-truck"></i><span class="visible-xs-inline">GLS</span>
								</a>
							</li>

							<li>
								<a href="{{ route('admin.sanatorium.orders.orders.delete', $order->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::orders/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::orders/common.tabs.attributes') }}}</a></li>
					@if ( Sentinel::hasAnyAccess(['superuser']) )
					<li role="presentation"><a href="#debug" aria-controls="debug" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::orders/common.tabs.debug') }}}</a></li>
					@endif
				</ul>

				<div class="tab-content">
					
					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">
						
						@if ( $order->exists )
							
							<div class="row">
								<div class="col-sm-6">

									<h3>{{ trans('sanatorium/orders::cart.title') }}</h3>

									{{ Cart::unserialize($order->cart) }}

									<?php $items = Cart::items() ?>
									
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

									@hook('cart.summary.items')
									
										</tbody>

									</table>

								</div>
								<div class="col-sm-6">
									
									@if ( $order->isPaymentOpened() )
									<div class="scaffold-components text-right">

										<ul class="scaffold-components-list">
											
											@foreach( $supports as $supported_method )
											<li class="order-action component-{{ $supported_method }}" data-id="{{ $order->id }}" data-action="{{ $supported_method }}">
												<i class="fa icon-{{ $supported_method }}"></i>
												<span>{{ trans('sanatorium/orders::orders/common.actions.'.$supported_method) }}</span>
											</li>
											@endforeach

										</ul>

									</div>
									@endif
									
									<p class="well">
										{{ $order->payment_provider_status_human_readable }}
									</p>

								</div>
							</div>
									
							<div class="row">

								<div class="col-sm-6">
									<h4>{{ trans('sanatorium/orders::cart.billing.title') }}</h4>
									<?php $address = Sanatorium\Addresses\Models\Address::find($order->address_billing_id) ?>

									<div class="form-group">
										<input type="text" class="form-control" name="billing[name]" value="{{ $address->name }}" placeholder="{{ trans('sanatorium/orders::cart.billing.name') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="billing[street]" value="{{ $address->street }}" placeholder="{{ trans('sanatorium/orders::cart.billing.street') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="billing[city]" value="{{ $address->city }}" placeholder="{{ trans('sanatorium/orders::cart.billing.city') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="billing[postcode]" value="{{ $address->postcode }}" placeholder="{{ trans('sanatorium/orders::cart.billing.postcode') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="billing[country]" value="{{ $address->country }}" placeholder="{{ trans('sanatorium/orders::cart.billing.country') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="billing[ic]" value="{{ $address->ic }}" placeholder="{{ trans('sanatorium/orders::cart.company.ic') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="billing[dic]" value="{{ $address->dic }}" placeholder="{{ trans('sanatorium/orders::cart.company.dic') }}">
									</div>

								</div>
								<div class="col-sm-6">
									<h4>{{ trans('sanatorium/orders::cart.delivery.title') }}</h4>
									<?php $address = Sanatorium\Addresses\Models\Address::find($order->address_delivery_id) ?>

									<div class="form-group">
										<input type="text" class="form-control" name="delivery[name]" value="{{ $address->name }}" placeholder="{{ trans('sanatorium/orders::cart.delivery.name') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="delivery[street]" value="{{ $address->street }}" placeholder="{{ trans('sanatorium/orders::cart.delivery.street') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="delivery[city]" value="{{ $address->city }}" placeholder="{{ trans('sanatorium/orders::cart.delivery.city') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="delivery[postcode]" value="{{ $address->postcode }}" placeholder="{{ trans('sanatorium/orders::cart.delivery.postcode') }}">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="delivery[country]" value="{{ $address->country }}" placeholder="{{ trans('sanatorium/orders::cart.delivery.country') }}">
									</div>
								</div>

							</div>

							<div class="row">
								
								<div class="col-sm-6">
									<h4>{{ trans('sanatorium/orders::cart.totals.delivery') }}</h4>
									<?php $deliverytype = Sanatorium\Orders\Models\Deliverytype::find($order->delivery_type_id) ?>
									<p>{{ $deliverytype->delivery_title }}</p>
									{!! $deliverytype->delivery_description !!}
									<p>{{ $deliverytype->price_vat }}</p>
								</div>

							</div>

						@endif

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('order_note', ' has-error') }}">

									<label for="order_note" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::orders/model.general.order_note_help') }}}"></i>
										{{{ trans('sanatorium/orders::orders/model.general.order_note') }}}
									</label>

									<input type="text" class="form-control" name="order_note" id="order_note" placeholder="{{{ trans('sanatorium/orders::orders/model.general.order_note') }}}" value="{{{ input()->old('order_note', $order->order_note) }}}">

									<span class="help-block">{{{ Alert::onForm('order_note') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('order_email', ' has-error') }}">

									<label for="order_email" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::orders/model.general.order_email_help') }}}"></i>
										{{{ trans('sanatorium/orders::orders/model.general.order_email') }}}
									</label>

									<input type="text" class="form-control" name="order_email" id="order_email" placeholder="{{{ trans('sanatorium/orders::orders/model.general.order_email') }}}" value="{{{ input()->old('order_email', $order->order_email) }}}">

									<span class="help-block">{{{ Alert::onForm('order_email') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('order_phone', ' has-error') }}">

									<label for="order_phone" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::orders/model.general.order_phone_help') }}}"></i>
										{{{ trans('sanatorium/orders::orders/model.general.order_phone') }}}
									</label>

									<input type="text" class="form-control" name="order_phone" id="order_phone" placeholder="{{{ trans('sanatorium/orders::orders/model.general.order_phone') }}}" value="{{{ input()->old('order_phone', $order->order_phone) }}}">

									<span class="help-block">{{{ Alert::onForm('order_email') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('user_id', ' has-error') }}">

									<label for="user_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::orders/model.general.user_id_help') }}}"></i>
										{{{ trans('sanatorium/orders::orders/model.general.user_id') }}}
									</label>

									<input type="text" class="form-control" name="user_id" id="user_id" placeholder="{{{ trans('sanatorium/orders::orders/model.general.user_id') }}}" value="{{{ input()->old('user_id', $order->user_id) }}}">

									<span class="help-block">{{{ Alert::onForm('user_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('payment_id', ' has-error') }}">

									<label for="payment_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::orders/model.general.payment_id_help') }}}"></i>
										{{{ trans('sanatorium/orders::orders/model.general.payment_id') }}}
									</label>

									<input type="text" class="form-control" name="payment_id" id="payment_id" placeholder="{{{ trans('sanatorium/orders::orders/model.general.payment_id') }}}" value="{{{ input()->old('payment_id', $order->payment_id) }}}">

									<span class="help-block">{{{ Alert::onForm('payment_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('delivery_id', ' has-error') }}">

									<label for="delivery_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::orders/model.general.delivery_id_help') }}}"></i>
										{{{ trans('sanatorium/orders::orders/model.general.delivery_id') }}}
									</label>

									<input type="text" class="form-control" name="delivery_id" id="delivery_id" placeholder="{{{ trans('sanatorium/orders::orders/model.general.delivery_id') }}}" value="{{{ input()->old('delivery_id', $order->delivery_id) }}}">

									<span class="help-block">{{{ Alert::onForm('delivery_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('payment_type_id', ' has-error') }}">

									<label for="payment_type_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::orders/model.general.payment_type_id_help') }}}"></i>
										{{{ trans('sanatorium/orders::orders/model.general.payment_type_id') }}}
									</label>

									<input type="text" class="form-control" name="payment_type_id" id="payment_type_id" placeholder="{{{ trans('sanatorium/orders::orders/model.general.payment_type_id') }}}" value="{{{ input()->old('payment_type_id', $order->payment_type_id) }}}">

									<span class="help-block">{{{ Alert::onForm('payment_type_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('delivery_type_id', ' has-error') }}">

									<label for="delivery_type_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::orders/model.general.delivery_type_id_help') }}}"></i>
										{{{ trans('sanatorium/orders::orders/model.general.delivery_type_id') }}}
									</label>

									<input type="text" class="form-control" name="delivery_type_id" id="delivery_type_id" placeholder="{{{ trans('sanatorium/orders::orders/model.general.delivery_type_id') }}}" value="{{{ input()->old('delivery_type_id', $order->delivery_type_id) }}}">

									<span class="help-block">{{{ Alert::onForm('delivery_type_id') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($order)
					</div>

					{{-- Tab: Debug --}}
					@if ( Sentinel::hasAnyAccess(['superuser']) )
					<div role="tabpanel" class="tab-pane fade" id="debug">
						<pre>{{ print_r(unserialize($order->cart)) }}</pre>
					</div>
					@endif

				</div>

			</div>

		</div>

	</form>

</section>
@stop
