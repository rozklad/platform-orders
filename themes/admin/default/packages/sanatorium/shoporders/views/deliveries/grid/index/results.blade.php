<script type="text/template" data-grid="delivery" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.delivery_status_id %></td>
			<td><%= r.delivery_type_id %></td>
			<td><%= r.delivery_money_id %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
