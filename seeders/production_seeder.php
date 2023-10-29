<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Bezhanov\Faker\Provider\Commerce;
use Hyperf\DbConnection\Db;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Commerce($faker));
        $randomArr = [true,false]; 

        for ($i=0; $i < 100; $i++) {
            $p_key = \Hyperf\DbConnection\Db::table('production')->insertGetId([
                'name' => $faker->productName(),
                'description' => $faker->text(),
                'price' => random_int(1,10000),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ],'p_key');

            InventorySeeder::insertInventory($p_key);

            HistorySeeder::insertHistory($p_key, $randomArr[random_int(0,1)]);
        }


    }
}
