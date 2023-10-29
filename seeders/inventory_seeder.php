<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }

    /**
     * 新增庫存 fake data
     *
     * @param integer $insertId
     * @return void
     */
    public static function insertInventory(int $insertId)
    {
        $builder = \Hyperf\DbConnection\Db::table('inventory');

        if ($insertId == 1 || $insertId == 2) {
            $amount = 100000000;
        } else if ($insertId == 3 || $insertId == 4) {
            $amount = 0;
        } else {
            $amount = random_int(0, 200);
        }

        $builder->insert([
            "p_key" => $insertId,
            "amount" => $amount,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);
    }
}
