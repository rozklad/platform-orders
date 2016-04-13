<script type="text/template" data-grid="paymenttype" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><a href="<%= r.edit_uri %>"><%= r.payment_title %></a></td>
			<td><%= r.code %></td>
			<td><%= r.money_min %></td>
			<td><%= r.money_max %></td>
			<td><%= r.payment_service %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
