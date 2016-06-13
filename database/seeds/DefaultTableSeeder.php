<?php namespace Sanatorium\Orders\Database\Seeds;

use DB;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DefaultTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$attributesRepo = app('Platform\Attributes\Repositories\AttributeRepositoryInterface');

		$attributes = [
			[
				'name' => 'Delivery title',
				'type' => 'input',
				'description' => 'Delivery title',
				'slug' => 'delivery_title',
				'namespace' => \Sanatorium\Orders\Models\DeliveryType::getEntityNamespace()
			],
			[
				'name' => 'Delivery description',
				'type' => 'wysiwyg',
				'description' => 'Delivery description',
				'slug' => 'delivery_description',
				'namespace' => \Sanatorium\Orders\Models\DeliveryType::getEntityNamespace()
			],
			[
				'name' => 'Payment title',
				'type' => 'input',
				'description' => 'Payment title',
				'slug' => 'payment_title',
				'namespace' => \Sanatorium\Orders\Models\PaymentType::getEntityNamespace()
			],
			[
				'name' => 'Payment description',
				'type' => 'wysiwyg',
				'description' => 'Payment description',
				'slug' => 'payment_description',
				'namespace' => \Sanatorium\Orders\Models\PaymentType::getEntityNamespace()
			],
			[
				'name' => 'Order email',
				'type' => 'input',
				'description' => 'Order email',
				'slug' => 'order_email',
				'namespace' => \Sanatorium\Orders\Models\Order::getEntityNamespace()
			],
			[
				'name' => 'Order phone',
				'type' => 'input',
				'description' => 'Order phone',
				'slug' => 'order_phone',
				'namespace' => \Sanatorium\Orders\Models\Order::getEntityNamespace()
			],
			[
				'name' => 'Order note',
				'type' => 'textarea',
				'description' => 'Order note',
				'slug' => 'order_note',
				'namespace' => \Sanatorium\Orders\Models\Order::getEntityNamespace()
			],
		];


		foreach( $attributes as $attribute )
		{
			$attributesRepo->firstOrCreate([
				'namespace'   => $attribute['namespace'],
				'name'        => $attribute['name'],
				'description' => $attribute['description'],
				'type'        => $attribute['type'],
				'slug'        => $attribute['slug'],
				'enabled'     => 1,
			]);
		}

		$prepared = [
				'deliverytypes' =>
					[
						[
							'delivery_title' => 'Default delivery',
							'delivery_description' => 'Default delivery description',
							'money_min' => 0,
							'money_max' => 1000000,
							'code' => 'DELCODE',
						]
					],
				'paymenttypes' =>
					[
						[
							'payment_title' => 'Default payment',
							'payment_description' => 'Default payment description'
						]
					]
		];

		foreach( $prepared['deliverytypes'] as $deliverytype )
		{
			\Sanatorium\Orders\Models\DeliveryType::create($deliverytype);
		}

		foreach( $prepared['paymenttypes'] as $paymenttype )
		{
			\Sanatorium\Orders\Models\PaymentType::create($paymenttype);
		}
	}

}
