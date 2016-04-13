<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_deliveries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('delivery_status_id')->nullable();
			$table->integer('delivery_type_id')->nullable();
			$table->integer('delivery_money_id')->nullable();
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
		Schema::drop('shop_deliveries');
	}

}
