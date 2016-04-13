<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('customer_id')->nullable();
			$table->integer('address_billing_id')->nullable();
			$table->integer('address_delivery_id')->nullable();
			$table->text('cart')->nullable();
			$table->integer('payment_id')->nullable();
			$table->integer('delivery_id')->nullable();
			$table->integer('payment_type_id')->nullable();
			$table->integer('delivery_type_id')->nullable();
			$table->integer('currency_id')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_orders');
	}

}
