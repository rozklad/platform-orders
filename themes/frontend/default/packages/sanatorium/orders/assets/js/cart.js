

var Cart = {

	defaults: {
		loadingClass: 'loading'
	},

	init: function(settings) {

		this.config = $.extend(this.defaults, settings);

		this.cart_url = window.cart_url;

		this.cacheSelectors();

		this.activateSummary();

		return this;
	},

	cacheSelectors: function() {

		this.$window = $(window);
		this.$body = $('body');
		this.$cartRows = $('[data-rowid]');

	},

	activateSummary: function() {

		this.activateDeleteRow();

		this.activateUpdateRow();

		this.activateSelectDelivery();

		this.activateSelectPayment();

	},

	activateSelectDelivery: function() {

		var self = this;

		$('[name="deliverytype"]').change(function(){

			var value = $(this).val();

			self.updateDelivery(value, $(this).parents('.cart-part:first'));

		});

	},

	activateSelectPayment: function() {

		var self = this;

		$('[name="paymenttype"]').change(function(){

			var value = $(this).val();

			self.updatePayment(value, $(this).parents('.cart-part:first'));

		});

	},

	updateDelivery: function(delivery, $cart_part) {

		var self = this,
			mode = 'update',
			url = url = self.cart_url[mode],
			data = {
				delivery_id: delivery,
				_token: $('table[data-token]').data('token')
			};

		$cart_part.addClass(self.config.loadingClass);

		$.ajax({
			url: url,
			type: 'POST',
			data: data
		}).success(function(response){

			$cart_part.removeClass(self.config.loadingClass);
		
			self.updateView(response);
		});
	},

	updatePayment: function(payment, $cart_part) {

		var self = this,
			mode = 'update',
			url = url = self.cart_url[mode],
			data = {
				payment_id: payment,
				_token: $('table[data-token]').data('token')
			};

		$cart_part.addClass(self.config.loadingClass);

		$.ajax({
			url: url,
			type: 'POST',
			data: data
		}).success(function(response){

			$cart_part.removeClass(self.config.loadingClass);
		
			self.updateView(response);
		});
	},

	updateProduct: function($row, quantity, $cart_part) {

		var self = this;

		var data = {
			rowid: $row.data('rowid'),
			quantity: quantity,
			_token: $row.parents('table:first').data('token')
		},
			mode = ( quantity == 0 ? 'delete' : 'update' ),
			url = self.cart_url[mode];

		$cart_part.addClass(self.config.loadingClass);

		$.ajax({
			url: url,
			type: 'POST',
			data: data
		}).success(function(response){
			if ( mode == 'delete' ) {
				$row.remove();
			}
			
			$cart_part.removeClass(self.config.loadingClass);
		
			self.updateView(response);
		});

	},

	activateDeleteRow: function() {

		var self = this;

		this.$cartRows.find('.cart-remove').click(function(event){
			event.preventDefault();
			
			self.updateProduct( 
				$(this).parents('[data-rowid]:first'),
				0,
				$(this).parents('.cart-part:first')
			 );

			return false;
		});
	},

	activateUpdateRow: function() {

		var self = this;

		this.$cartRows.find('[name="quantity"]').change(function(){

			self.updateProduct( 
				$(this).parents('[data-rowid]:first'),
				$(this).val(),
				$(this).parents('.cart-part:first')
			 );

			return false;
		});
	},

	updateView: function(response) {


		$('[data-total-count]').text(response['count']);

		if ( typeof response['items'] == 'object' ) {

			// Update item prices
			for ( var key in response['items'] ) {

				$('[data-rowid="'+key+'"]').find('[data-price-type="vat_quantity"]').text( response['items'][key]['vat_quantity'] );
			}
		}

		if ( typeof response['totals'] == 'object' ) {

			// Update total prices
			for ( var key in response['totals'] ) {

				$('[data-'+key+']').text( response['totals'][key] );

			}

		}

	},

};

$(function(){

	Cart.init();

});