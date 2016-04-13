@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{ trans('sanatorium/orders::orders/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/css/daterangepicker-bs3.css', 'style') }}

{{ Asset::queue('moment', 'moment/js/moment.js', 'jquery') }}
{{ Asset::queue('data-grid', 'cartalyst/js/data-grid.js', 'jquery') }}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('index', 'sanatorium/orders::orders/js/index.js', 'platform') }}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/js/daterangepicker.js', 'jquery') }}
{{ Asset::queue('jscookie', 'sanatorium/orders::jscookie.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
<script type="text/javascript">

var CustomFilters = {

	delimiter: '/',

	buildUri: function(filters) {

		var stringUri = '';

		for ( var key in filters ) {

			stringUri += this.delimiter + filters[key];

		}

		return stringUri;

	},

	changeUri: function(filters) {

		var url = this.buildUri(filters),
			$grid = $('#data-grid'),
			source = $grid.data('source'),
			route = window.location.protocol+'//'+window.location.host+window.location.pathname;

		window.location = route + "#" + url;

	}

};

window.custom_filters = [];
$(function(){

	var cookieFilters = Cookies.getJSON('custom_filters_all');

	if ( typeof cookieFilters !== 'undefined' ) {

		window.custom_filters = cookieFilters;

		$(window).trigger('custom_filters');

		for ( var key in cookieFilters ) {

			var current_filter = cookieFilters[key];

			if ( typeof current_filter != 'undefined' ) {

				if ( current_filter != '' ) {

					$('[data-custom-filter="'+current_filter+'"]').attr('checked', 'checked');
				}

			}
		}
		
	}

	$('[data-custom-filter]').change(function(event){

		window.custom_filters = [];

		$('[data-custom-filter]:checked').each(function(){

			window.custom_filters.push($(this).data('custom-filter'));

		});

		$(window).trigger('custom_filters');

		CustomFilters.changeUri(window.custom_filters);

		// Remember filters for 7 days
		Cookies.set('custom_filters_all', window.custom_filters, { expires: 7 });

	});

	$(window).on('hashchange', function()
	{
		
	});

	
	if ( window.location.hash ) {

		var hashroute = window.location.hash.replace('#', ''),
			hashroute_array = hashroute.split('/');

		for ( var key in hashroute_array ) {

			var current_filter = hashroute_array[key];

			if ( typeof current_filter != 'undefined' ) {

				if ( current_filter != '' ) {

					$('[data-custom-filter="'+current_filter+'"]').attr('checked', 'checked');
				}

			}
		}

		$('[data-custom-filter]:first').trigger('change');
	}

});
</script>
@stop

{{-- Inline styles --}}
@section('styles')
@parent
<style type="text/css">
.nav-tabs.nav-tabs-left {
	width: 25%;
}
.nav-tabs.nav-tabs-left ~ .tab-content {
	width: 75%;
}
</style>
@stop

{{-- Page content --}}
@section('page')

{{-- Grid --}}
<section class="panel panel-default panel-grid">

	{{-- Grid: Header --}}
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

					<span class="navbar-brand">{{{ trans('sanatorium/orders::orders/common.title') }}}</span>

				</div>

				{{-- Grid: Actions --}}
				<div class="collapse navbar-collapse" id="actions">

					<ul class="nav navbar-nav navbar-left">

						<li class="disabled">
							<a class="disabled" data-grid-bulk-action="customer_repair" data-toggle="tooltip" data-original-title="{{{ trans('sanatorium/orders::orders/common.actions.customer.repair') }}}">
								<i class="fa fa-user"></i> <span class="visible-xs-inline">{{{ trans('sanatorium/orders::orders/common.actions.customer.repair') }}}</span>
							</a>
						</li>
						
						<li class="danger disabled">
							<a data-grid-bulk-action="delete" data-toggle="tooltip" data-target="modal-confirm" data-original-title="{{{ trans('action.bulk.delete') }}}">
								<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.bulk.delete') }}}</span>
							</a>
						</li>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle tip" data-toggle="dropdown" role="button" aria-expanded="false" data-original-title="{{{ trans('action.export') }}}">
								<i class="fa fa-download"></i> <span class="visible-xs-inline">{{{ trans('action.export') }}}</span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><a data-download="json"><i class="fa fa-file-code-o"></i> JSON</a></li>
								<li><a data-download="csv"><i class="fa fa-file-excel-o"></i> CSV</a></li>
								<li><a data-download="pdf"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
							</ul>
						</li>

						<li class="primary">
							<a href="{{ route('admin.sanatorium.orders.orders.create') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.create') }}}">
								<i class="fa fa-plus"></i> <span class="visible-xs-inline">{{{ trans('action.create') }}}</span>
							</a>
						</li>

					</ul>

					{{-- Grid: Filters --}}
					<form class="navbar-form navbar-right" method="post" accept-charset="utf-8" data-search data-grid="order" role="form">

						<div class="input-group">

							<span class="input-group-btn">

								<button class="btn btn-default" type="button" disabled>
									{{{ trans('common.filters') }}}
								</button>

								<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>

								<ul class="dropdown-menu" role="menu">

									<li>
										<a data-grid="order" data-filter="enabled:1" data-label="enabled::{{{ trans('common.all_enabled') }}}" data-reset>
											<i class="fa fa-eye"></i> {{{ trans('common.show_enabled') }}}
										</a>
									</li>

									<li>
										<a data-toggle="tooltip" data-placement="top" data-original-title="" data-grid="order" data-filter="enabled:0" data-label="enabled::{{{ trans('common.all_disabled') }}}" data-reset>
											<i class="fa fa-eye-slash"></i> {{{ trans('common.show_disabled') }}}
										</a>
									</li>

									<li class="divider"></li>

									<li>
										<a data-grid-calendar-preset="day">
											<i class="fa fa-calendar"></i> {{{ trans('date.day') }}}
										</a>
									</li>

									<li>
										<a data-grid-calendar-preset="week">
											<i class="fa fa-calendar"></i> {{{ trans('date.week') }}}
										</a>
									</li>

									<li>
										<a data-grid-calendar-preset="month">
											<i class="fa fa-calendar"></i> {{{ trans('date.month') }}}
										</a>
									</li>

								</ul>

								<button class="btn btn-default hidden-xs" type="button" data-grid-calendar data-range-filter="created_at">
									<i class="fa fa-calendar"></i>
								</button>

							</span>

							<input class="form-control " name="filter" type="text" placeholder="{{{ trans('common.search') }}}">

							<span class="input-group-btn">

								<button class="btn btn-default" type="submit">
									<span class="fa fa-search"></span>
								</button>

								<button class="btn btn-default" data-grid="order" data-reset>
									<i class="fa fa-refresh fa-sm"></i>
								</button>

							</span>

						</div>

					</form>

				</div>

			</div>

		</nav>

	</header>

	<div class="panel-body">

		<div class="row">

			<div class="col-md-12">

				<?php

				$filters = [
					'status' => [
						'comparison' => '=',
					],
					'deliverytype' => [
						'comparison' => '=',
					],
					'paymenttype' => [
						'comparison' => '=',
					]
				];
				?>

				@include('sanatorium/orders::filters')
 
			</div>

		</div>

		{{-- Grid: Applied Filters --}}
		<div class="btn-toolbar" role="toolbar" aria-label="data-grid-applied-filters">

			<div id="data-grid_applied" class="btn-group" data-grid="order"></div>

		</div>

	</div>

	{{-- Grid: Table --}}
	<div class="table-responsive">

		<table id="data-grid" class="table table-hover" data-source="{{ route('admin.sanatorium.orders.orders.grid') }}" data-grid="order">
			<thead>
				<tr>
					<th><input data-grid-checkbox="all" type="checkbox"></th>
					<th class="sortable" data-sort="id">{{{ trans('sanatorium/orders::orders/model.general.id') }}}</th>
					<th>{{{ trans('sanatorium/orders::orders/model.general.delivery_address') }}}</th>
					<th class="sortable" data-sort="payment_type">{{{ trans('sanatorium/orders::orders/model.general.payment_type') }}}</th>
					<th class="sortable" data-sort="delivery_type">{{{ trans('sanatorium/orders::orders/model.general.delivery_type') }}}</th>
					<th class="sortable" data-sort="created_at">{{{ trans('sanatorium/orders::orders/model.general.created_at') }}}</th>
					<th>{{{ trans('sanatorium/orders::orders/model.general.status') }}}</th>
					<th></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>

	</div>

	<footer class="panel-footer clearfix">

		{{-- Grid: Pagination --}}
		<div id="data-grid_pagination" data-grid="order"></div>

	</footer>

	{{-- Grid: templates --}}
	@include('sanatorium/orders::orders/grid/index/results')
	@include('sanatorium/orders::orders/grid/index/pagination')
	@include('sanatorium/orders::orders/grid/index/filters')
	@include('sanatorium/orders::orders/grid/index/no_results')

</section>

@if (config('platform.app.help'))
	@include('sanatorium/orders::orders/help')
@endif

@stop
