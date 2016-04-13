<ul class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-white">
<?php $filter_index = 0; ?>
@foreach( $filters as $slug => $settings )
	<?php 
	$filter_index++; 
	if ( $slug == 'tags' ) {
		?>
		<li class="{{ ($filter_index == 1 ? 'active' : '') }}">
 			<a href="#{{ $slug }}" data-toggle="tab">
 				Tags
 			</a>
 		</li>
 		<?php
	} elseif ( $slug == 'status' ) {
		?>
		<li class="{{ ($filter_index == 1 ? 'active' : '') }}">
 			<a href="#{{ $slug }}" data-toggle="tab">
 				Status
 			</a>
 		</li>
 		<?php
	} elseif ( $slug == 'deliverytype' ) {
		?>
		<li class="{{ ($filter_index == 1 ? 'active' : '') }}">
 			<a href="#{{ $slug }}" data-toggle="tab">
 				{{{ trans('sanatorium/orders::orders/model.general.delivery_type') }}}
 			</a>
 		</li>
 		<?php
	} elseif ( $slug == 'paymenttype' ) {
		?>
		<li class="{{ ($filter_index == 1 ? 'active' : '') }}">
 			<a href="#{{ $slug }}" data-toggle="tab">
 				{{{ trans('sanatorium/orders::orders/model.general.payment_type') }}}
 			</a>
 		</li>
 		<?php
	} else {
		
		$attribute = \Platform\Attributes\Models\Attribute::whereSlug($slug)->first();
		?>
		<li class="{{ ($filter_index == 1 ? 'active' : '') }}">
 			<a href="#{{ $slug }}" data-toggle="tab">
 				{{ $attribute->name }}
 			</a>
 		</li>
		<?php } ?>
@endforeach
</ul>
<div class="tab-content filters">
	<?php $filter_index = 0; ?>
	@foreach( $filters as $slug => $settings )
	<?php 
	$filter_index++; 
	if ( $slug == 'tags' ) {
		$attribute_id = 'tags';
		$attribute_name = 'persist_tags';
		$comparison = $settings['comparison'];
		?>
		<div class="tab-pane {{ ($filter_index == 1 ? 'active' : '') }}" id="{{ $slug }}">
		@if ( !empty($tags) )
			@foreach ($tags as $tag)
				<?php $key = $tag; ?>
				<label for="checkbox-{{ $attribute_id }}-{{ $key }}" class="filter-checkbox">
 					<input type="checkbox" value="1" id="checkbox-{{ $attribute_id }}-{{ $key }}"
 					data-custom-filter="{{ $attribute_name }}:{{ $comparison }}:{{ ($comparison == 'like' ? '%' : '') }}{{ $key }}{{ ($comparison == 'like' ? '%' : '') }}:{{ $attribute_id }}">
 					{{ $tag }}
 				</label>
			@endforeach
		@endif
		</div>
		<?php
	} elseif ( $slug == 'status' ) {
		$attribute_id = 'statuses.id';
		$attribute_name = 'statuses..';
		$comparison = $settings['comparison'];
		?>
		<div class="tab-pane {{ ($filter_index == 1 ? 'active' : '') }}" id="{{ $slug }}">
		@if ( !empty($statuses) )
			@foreach ($statuses as $status)
				<?php $key = $status->id; ?>
				<label for="checkbox-{{ $attribute_id }}-{{ $key }}" class="filter-checkbox">
 					<input type="checkbox" value="1" id="checkbox-{{ $attribute_id }}-{{ $key }}"
 					data-custom-filter="{{ $attribute_name }}:{{ $comparison }}:{{ ($comparison == 'like' ? '%' : '') }}{{ $key }}{{ ($comparison == 'like' ? '%' : '') }}:{{ $attribute_id }}">
 					{{ $status->name }}
 				</label>
			@endforeach
		@endif
		</div>
		<?php
	} elseif ( $slug == 'deliverytype' ) {
		$deliverytypes = \Sanatorium\Orders\Models\Deliverytype::all();
		$attribute_id = 'shop_delivery_types.id';
		$attribute_name = 'deliverytype..';
		$comparison = $settings['comparison'];
		?>
		<div class="tab-pane {{ ($filter_index == 1 ? 'active' : '') }}" id="{{ $slug }}">
		@if ( !empty($deliverytypes) )
			@foreach ($deliverytypes as $deliverytype)
				<?php $key = $deliverytype->id; ?>
				<label for="checkbox-{{ $attribute_id }}-{{ $key }}" class="filter-checkbox">
 					<input type="checkbox" value="1" id="checkbox-{{ $attribute_id }}-{{ $key }}"
 					data-custom-filter="{{ $attribute_name }}:{{ $comparison }}:{{ ($comparison == 'like' ? '%' : '') }}{{ $key }}{{ ($comparison == 'like' ? '%' : '') }}:{{ $attribute_id }}">
 					{{ $deliverytype->delivery_title }}
 				</label>
			@endforeach
		@endif
		</div>
		<?php
	} elseif ( $slug == 'paymenttype' ) {
		$paymenttypes = \Sanatorium\Orders\Models\Paymenttype::all();
		$attribute_id = 'shop_payment_types.id';
		$attribute_name = 'paymenttype..';
		$comparison = $settings['comparison'];
		?>
		<div class="tab-pane {{ ($filter_index == 1 ? 'active' : '') }}" id="{{ $slug }}">
		@if ( !empty($paymenttypes) )
			@foreach ($paymenttypes as $paymenttype)
				<?php $key = $paymenttype->id; ?>
				<label for="checkbox-{{ $attribute_id }}-{{ $key }}" class="filter-checkbox">
 					<input type="checkbox" value="1" id="checkbox-{{ $attribute_id }}-{{ $key }}"
 					data-custom-filter="{{ $attribute_name }}:{{ $comparison }}:{{ ($comparison == 'like' ? '%' : '') }}{{ $key }}{{ ($comparison == 'like' ? '%' : '') }}:{{ $attribute_id }}">
 					{{ $paymenttype->payment_title }}
 				</label>
			@endforeach
		@endif
		</div>
		<?php
	} else {
	
	$attribute = \Platform\Attributes\Models\Attribute::whereSlug($slug)->first();
	?>
	<div class="tab-pane {{ ($filter_index == 1 ? 'active' : '') }}" id="{{ $slug }}">
		<?php 
			// Attribute filter: Industry
			$attribute_id = $attribute->id;
			$attribute_name = $slug;
			$comparison = $settings['comparison'];
			?>
			@foreach( $attribute->options as $key => $value)
			<label for="checkbox-{{ $attribute_id }}-{{ $key }}" class="filter-checkbox">
					<input type="checkbox" value="1" id="checkbox-{{ $attribute_id }}-{{ $key }}"
					data-custom-filter="{{ $attribute_name }}:{{ $comparison }}:{{ ($comparison == 'like' ? '%' : '') }}{{ $key }}{{ ($comparison == 'like' ? '%' : '') }}:{{ $attribute_id }}">
					{{ $value }}
				</label>
			@endforeach
		</div>
		<?php } ?>
@endforeach
</div>