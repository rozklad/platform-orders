<script type="text/template" data-grid="order" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td>
				<% if ( typeof r.deliveryaddress != 'undefined' && r.deliveryaddress ) { %>
					<%= r.deliveryaddress.name %><br>
					<%= r.deliveryaddress.street %>, <%= r.deliveryaddress.city %><br>
					<%= r.deliveryaddress.postcode %> <%= r.deliveryaddress.country %>
				<% } %>
			</td>
			<td>
				<% if ( typeof r.paymenttype != 'undefined' && r.paymenttype ) { %>
					<%= r.paymenttype.payment_title %>
				<% } %>
			</td>
			<td>
				<% if ( typeof r.deliverytype != 'undefined' && r.deliverytype ) { %>
					<%= r.deliverytype.delivery_title %>
				<% } %>
			</td>
			<td><%= r.created_at %></td>
			<td>
				<select name="status" data-id="<%= r.id %>" class="select2" data-init-plugin="select2" style="width:135px;" data-status-change data-status-route="{{ route('admin.sanatorium.orders.orders.status') }}" data-msg-success="{{ trans('sanatorium/orders::orders/common.actions.status_change.success') }}">
					<option value="0" data-css="badge badge-default">{{ trans('sanatorium/status::statuses/common.not_specified') }}</option>
				@foreach($statuses as $status)
					<option value="{{ $status->id }}" data-css="{{ $status->css_class }}"
						<% if ( typeof r.status != 'undefined' ) { %>
							<% if ( typeof r.status.id != 'undefined' ) { %>
								<% if ( r.status.id == '{{ $status->id }}' ) { %>
									selected
								<% } %>
							<% } %>
						<% } %>
					>{{ $status->name }}</option>
				@endforeach 
				</select>
			</td>
			<td>
				<a href="<%= r.edit_uri %>" class="btn btn-default" data-toggle="tooltip" title="{{ trans('action.show') }}">
					<i class="fa fa-eye"></i>
				</a>
				<button class="btn btn-default" data-toggle="tooltip" title="{{ trans('sanatorium/orders::orders/common.actions.default_email.send') }}" data-send data-id="<%= r.id %>" data-route="{{ route('admin.sanatorium.orders.orders.send') }}" data-msg-success="{{ trans('sanatorium/orders::orders/common.actions.default_email.success') }}">
					<i class="fa fa-envelope"></i>
				</button>
				<button class="btn btn-default" data-toggle="tooltip" title="{{ trans('sanatorium/orders::orders/common.actions.default_email.forgot') }}" data-forgot data-id="<%= r.id %>" data-route="{{ route('admin.sanatorium.orders.orders.forgot') }}" data-msg-success="{{ trans('sanatorium/orders::orders/common.actions.forgot.success') }}">
					<i class="fa fa-cart-arrow-down"></i>
				</button>
				<a href="<%= r.customer_uri %>" class="btn btn-default" data-toggle="tooltip" title="{{ trans('sanatorium/orders::orders/common.actions.customer.show') }}">
					<i class="fa fa-user"></i>
				</a>
			</td>
		</tr>

	<% }); %>

</script>
