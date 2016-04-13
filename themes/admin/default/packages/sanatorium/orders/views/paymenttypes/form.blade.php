@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/orders::paymenttypes/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}
{{ Asset::queue('selectize', 'selectize/js/selectize.js', 'jquery') }}
{{ Asset::queue('selectize', 'selectize/css/selectize.bootstrap3.css') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
<script type="text/javascript">
	$(function(){
		$('#payment_service').selectize({
			persist: false,
			maxItems: 1,
			create: false,
			allowEmptyOption: false,
			valueField: 'class',
			labelField: 'name',
			searchField: ['name'],
			sortField: [
				{field: 'name', direction: 'asc'}
			],
			items: [
				'{{ $paymenttype->payment_service ? $paymenttype->payment_service : 'default' }}'
			],
			options: [
				@foreach( $services as $class )
					<?php $service = new $class; ?>
					{ name: '{{ $service->name }}', class: '{{ $class }}', description: '{{ $service->description }}' },
				@endforeach
			],
			render: {
				item: function(item, escape) {
					return '<div>' +
						(item.name ? '<strong class="name">' + item.name + '</strong><br>' : '') +
						(item.description ? '<span class="description">' + item.description + '</span>' : '') +
					'</div>';
				},
				option: function(item, escape) {
					return '<div>' +
						(item.name ? '<strong class="name">' + item.name + '</strong><br>' : '') +
						(item.description ? '<span class="description">' + item.description + '</span>' : '') +
					'</div>';
				}
			}
		});
	});
</script>
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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.orders.paymenttypes.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $paymenttype->exists ? $paymenttype->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($paymenttype->exists)
							<li>
								<a href="{{ route('admin.sanatorium.orders.paymenttypes.delete', $paymenttype->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::paymenttypes/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::paymenttypes/common.tabs.attributes') }}}</a></li>
					<li role="presentation"><a href="#deliveries" aria-controls="deliveries" role="tab" data-toggle="tab">{{{ trans('sanatorium/orders::paymenttypes/common.tabs.deliveries') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('code', ' has-error') }}">

									<label for="code" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::paymenttypes/model.general.code_help') }}}"></i>
										{{{ trans('sanatorium/orders::paymenttypes/model.general.code') }}}
									</label>

									<input type="text" class="form-control" name="code" id="code" placeholder="{{{ trans('sanatorium/orders::paymenttypes/model.general.code') }}}" value="{{{ input()->old('code', $paymenttype->code) }}}">

									<span class="help-block">{{{ Alert::onForm('code') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('money_min', ' has-error') }}">

									<label for="money_min" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::paymenttypes/model.general.money_min_help') }}}"></i>
										{{{ trans('sanatorium/orders::paymenttypes/model.general.money_min') }}}
									</label>

									<input type="text" class="form-control" name="money_min" id="money_min" placeholder="{{{ trans('sanatorium/orders::paymenttypes/model.general.money_min') }}}" value="{{{ input()->old('money_min', $paymenttype->money_min) }}}">

									<span class="help-block">{{{ Alert::onForm('money_min') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('money_max', ' has-error') }}">

									<label for="money_max" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::paymenttypes/model.general.money_max_help') }}}"></i>
										{{{ trans('sanatorium/orders::paymenttypes/model.general.money_max') }}}
									</label>

									<input type="text" class="form-control" name="money_max" id="money_max" placeholder="{{{ trans('sanatorium/orders::paymenttypes/model.general.money_max') }}}" value="{{{ input()->old('money_max', $paymenttype->money_max) }}}">

									<span class="help-block">{{{ Alert::onForm('money_max') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('payment_service', ' has-error') }}">

									<label for="payment_service" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/orders::paymenttypes/model.general.payment_service_help') }}}"></i>
										{{{ trans('sanatorium/orders::paymenttypes/model.general.payment_service') }}}
									</label>

									<select name="payment_service" class="payment_service" id="payment_service"></select>

									<span class="help-block">{{{ Alert::onForm('payment_service') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($paymenttype)
					</div>

					{{-- Tab: Deliveries --}}
					<div role="tabpanel" class="tab-pane fade" id="deliveries">
						<fieldset>
						@foreach(Sanatorium\Orders\Models\DeliveryType::all() as $delivery)
							<div class="form-group">
								<input type="checkbox" name="deliverytypes[]" value="{{ $delivery->id }}" id="delivery-{{ $delivery->id }}" {{ ( in_array($delivery->id, $paymenttype->deliverytypes()->select('shop_delivery_types.id')->lists('shop_delivery_types.id')->toArray() ) ? 'checked="checked"' : '' ) }}>
								<label for="delivery-{{ $delivery->id }}">
									{{ $delivery->delivery_title }}
								</label>
							</div>
						@endforeach
						</fieldset>
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
