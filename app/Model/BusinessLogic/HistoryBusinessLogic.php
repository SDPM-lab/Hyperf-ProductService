<?php

namespace App\Model\BusinessLogic;

use App\Model\HistoryModel;

class HistoryBusinessLogic
{

    /**
     * 新增流水帳
     *
     * @param integer $p_key
     * @param string  $o_key
     * @param integer $amount
     * @param string  $type
     * @return integer | null 新增是否成功 成功回傳新增 key
     */
    static function createHistory( $p_key,  $o_key,  $amount,  $type): ?int
    {

        $history = [
            "p_key"  => $p_key,
            "o_key"  => $o_key,
            "amount" => $amount,
            "type"   => $type,
        ];

        $insertID = HistoryModel::insertGetId($history);

        return $insertID;
    }

    /**
     * 判斷流水帳是否重複
     *
     * @param integer $p_key
     * @param string  $o_key
     * @param string  $type
     */
    static function verifyType($p_key, $o_key, $type)
    {
        $historyEntity = HistoryModel::where('o_key', $o_key)
                                      ->where('p_key', $p_key)
                                      ->where('type', $type)
                                      ->first();

        return $historyEntity;
    }

    /**
     * 刪除庫存記錄
     *
     * @param integer $h_key
     * @return void
     */
    static function delete($h_key)
    {
        HistoryModel::where('h_key',$h_key)->delete();
    }

    /**
     * 驗證商品是否有新增退貨或補償
     *
     * @param string $o_key
     */
    static function verifyCreated($o_key)
    {
        $historyEntity = HistoryModel::where('o_key', $o_key)
                                      ->whereNotIn('type', ["reduce", "compensate"])
                                      ->where('type', "create")
                                      ->first();
        return $historyEntity;
    }
}
