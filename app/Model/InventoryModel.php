<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use App\Utils\Log;
use Hyperf\DbConnection\Db;

/**
 */
class InventoryModel extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'inventory';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['p_key','amount'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];

    /**
     * 新增庫存與流水帳 transcation
     *
     * @param integer $p_key
     * @param string  $o_key
     * @param integer $addAmount
     * @param integer $nowAmount
     * @param string $type
     * @return boolean
     */
    public function addInventoryTransaction( $p_key, $o_key, $addAmount, $nowAmount, $type):bool
    {
        $historyData = [
            "p_key" => $p_key,
            "o_key" => $o_key,
            "amount" => $addAmount,
            "type" => $type,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ];

        try {
            Db::beginTransaction();

            DB::table("history")->insert($historyData);

            $inventory = [
                "amount" => $nowAmount + $addAmount,
                "updated_at" => date("Y-m-d H:i:s")
            ];

            DB::table("inventory")->where("p_key", $p_key)->update($inventory);

            Db::commit();

        } catch (\Exception $e) {
            Log::getInstance()->error('[ERROR] {exception}', ['exception' => $e]);
            Db::rollBack();

            return false;
        }
        return true;
    }

    /**
     * 減少庫存數量與增加流水帳 transcation
     *
     * @param integer $p_key
     * @param string  $o_key
     * @param integer $reduceAmount
     * @param integer $nowAmount
     * @return boolean
     */
    public function reduceInventoryTransaction( $p_key,  $o_key,  $reduceAmount,  $nowAmount):bool
    {
        $historyData = [
            "p_key" => $p_key,
            "o_key" => $o_key,
            "amount" => $reduceAmount,
            "type" => "create",
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ];
        
        try {
            Db::beginTransaction();

            DB::table("history")->insert($historyData);

            $inventory = [
                "amount" => $nowAmount - $reduceAmount,
                "updated_at" => date("Y-m-d H:i:s")
            ];
            
            DB::table("inventory")
                     ->where("p_key", $p_key)
                     ->where("amount",">=", $reduceAmount)
                     ->update($inventory);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Log::getInstance()->error('[ERROR] {exception}', ['exception' => $e]);
            Db::rollBack();
            return false;
        }
    }
}
