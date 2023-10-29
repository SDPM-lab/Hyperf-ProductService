<?php

namespace App\Model\BusinessLogic;


use App\Model\ProductionModel;

class InventoryBusinessLogic
{

    /**
     * 驗證商品 key 是否存在
     *
     * @param integer $p_key
     */
    static function verifyProductKey($p_key)
    {
        $productionEntity = ProductionModel::where('p_key',$p_key)->first();

        return $productionEntity;
    }
}
