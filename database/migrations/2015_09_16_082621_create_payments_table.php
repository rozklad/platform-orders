<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('payment_status_id')->nullable();
			$table->integer('payment_type_id')->nullable();
			$table->integer('money_id')->nullable();
			$table->text('provider_id')->nullable();
			$table->text('provider_status')->nullable();
			$table->text('provider_note')->nullable();
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
		Schema::drop('shop_payments');
	}

}
