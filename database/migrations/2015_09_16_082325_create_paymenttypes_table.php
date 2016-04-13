<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymenttypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_payment_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code')->nullable();
			$table->integer('money_min')->nullable();
			$table->integer('money_max')->nullable();
			$table->string('payment_service')->nullable();
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
		Schema::drop('shop_payment_types');
	}

}
