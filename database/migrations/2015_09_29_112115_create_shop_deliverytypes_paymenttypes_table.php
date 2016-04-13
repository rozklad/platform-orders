<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopDeliverytypesPaymenttypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_deliverytypes_paymenttypes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('payment_type_id');
			$table->integer('delivery_type_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_deliverytypes_paymenttypes');
	}

}
