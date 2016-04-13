
<div class="row">
	<div class="col-xs-6">
		<a href="{{ URL::to('/') }}" class="btn btn-default">
			{{ trans('sanatorium/orders::cart.actions.continue') }}
		</a>
	</div>
	<div class="col-xs-6 text-right">
		<button type="submit" class="btn btn-success">
			{{ trans('sanatorium/orders::cart.actions.place') }}
		</button>
	</div>
</div>