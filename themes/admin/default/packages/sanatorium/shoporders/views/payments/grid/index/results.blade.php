<script type="text/template" data-grid="payment" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.payment_status_id %></td>
			<td><%= r.payment_type_id %></td>
			<td><%= r.money_id %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
