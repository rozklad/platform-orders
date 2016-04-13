@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/orders::deliverytypes/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
<style type="text/css">
.general-attributes .btn.btn-primary.btn-lg {
	display: none;
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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.orders.deliverytypes.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $deliverytype->exists ? $deliverytype->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($deliverytype->exists)
							<li>
								<a href="{{ route('admin.sanatorium.orders.deliverytypes.delete', $deliverytype->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::deliverytypes/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::deliverytypes/common.tabs.attributes') }}}</a></li>
					<li role="presentation"><a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::deliverytypes/common.tabs.payments') }}}</a></li>
					<li role="presentation"><a href="#pricing-tab" aria-controls="pricing-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::deliverytypes/common.tabs.pricing') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">
						
						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('code', ' has-error') }}">

									<label for="code" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::deliverytypes/model.general.code_help') }}}"></i>
										{{{ trans('sanatorium/orders::deliverytypes/model.general.code') }}}
									</label>

									<input type="text" class="form-control" name="code" id="code" placeholder="{{{ trans('sanatorium/orders::deliverytypes/model.general.code') }}}" value="{{{ input()->old('code', $deliverytype->code) }}}">

									<span class="help-block">{{{ Alert::onForm('code') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('money_min', ' has-error') }}">

									<label for="money_min" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::deliverytypes/model.general.money_min_help') }}}"></i>
										{{{ trans('sanatorium/orders::deliverytypes/model.general.money_min') }}}
									</label>

									<input type="text" class="form-control" name="money_min" id="money_min" placeholder="{{{ trans('sanatorium/orders::deliverytypes/model.general.money_min') }}}" value="{{{ input()->old('money_min', $deliverytype->money_min) }}}">

									<span class="help-block">{{{ Alert::onForm('money_min') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('money_max', ' has-error') }}">

									<label for="money_max" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::deliverytypes/model.general.money_max_help') }}}"></i>
										{{{ trans('sanatorium/orders::deliverytypes/model.general.money_max') }}}
									</label>

									<input type="text" class="form-control" name="money_max" id="money_max" placeholder="{{{ trans('sanatorium/orders::deliverytypes/model.general.money_max') }}}" value="{{{ input()->old('money_max', $deliverytype->money_max) }}}">

									<span class="help-block">{{{ Alert::onForm('money_max') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('delivery_service', ' has-error') }}">

									<label for="delivery_service" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::deliverytypes/model.general.delivery_service_help') }}}"></i>
										{{{ trans('sanatorium/orders::deliverytypes/model.general.delivery_service') }}}
									</label>

									<input type="text" class="form-control" name="delivery_service" id="delivery_service" placeholder="{{{ trans('sanatorium/orders::deliverytypes/model.general.delivery_service') }}}" value="{{{ input()->old('delivery_service', $deliverytype->delivery_service) }}}">

									<span class="help-block">{{{ Alert::onForm('delivery_service') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($deliverytype)
					</div>

					{{-- Tab: Deliveries --}}
					<div role="tabpanel" class="tab-pane fade" id="payments">
						<fieldset>
						@foreach(Sanatorium\Orders\Models\PaymentType::all() as $payment)
							<div class="form-group">
								<input type="checkbox" name="paymenttypes[]" value="{{ $payment->id }}" id="payment-{{ $payment->id }}" {{ ( in_array($payment->id, $deliverytype->paymenttypes()->select('shop_payment_types.id')->lists('shop_payment_types.id')->toArray() ) ? 'checked="checked"' : '' ) }}>
								<label for="payment-{{ $payment->id }}">
									{{ $payment->payment_title }}
								</label>
							</div>
						@endforeach
						</fieldset>
					</div>

					{{-- Tab: Pricing --}}
					<div role="tabpanel" class="tab-pane fade" id="pricing-tab">
						@pricing($deliverytype)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
