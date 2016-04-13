<section class="panel panel-default panel-help">

	<header class="panel-heading">

		<h4>
			<i class="fa fa-life-ring" data-toggle="popover" data-content="{{{ trans('common.help.setting') }}}"></i> {{{ trans('common.help.title') }}}

			<a class="panel-close small pull-right collapsed tip" data-original-title="{{{ trans('action.collapse') }}}" data-toggle="collapse" href="#help-body" aria-expanded="false" aria-controls="help-body"></a>

		</h4>

	</header>

	<div class="panel-body collapse" id="help-body">

		<div class="row">

			<div class="col-md-10 col-md-offset-1 help">

				@content('sanatorium-orders-deliverystatuses-help', 'sanatorium/orders::deliverystatuses/content/help.md')

			</div>

		</div>

	</div>

</section>
