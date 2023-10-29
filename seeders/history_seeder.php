<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class HistorySeeder extends Seeder
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

    public static function insertHistory(int $p_key, bool $type)
    {
        $builder = \Hyperf\DbConnection\Db::table('history');

        $o_key = sha1(random_int(1, 10) . random_int(1, 1000) . date("Y-m-d H:i:s") . random_int(0, 100000));

        $builder->insert([
            "p_key"  => $p_key,
            "o_key"  => $o_key,
            "amount" => random_int(1,300),
            "type"   => "create",
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);

        // if type == true, seed reduce data
        if($type){
            $builder->insert([
                "p_key" => $p_key,
                "o_key" => $o_key,
                "amount" => random_int(1, 100),
                "type" => "reduce",
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
        }
    }
}
