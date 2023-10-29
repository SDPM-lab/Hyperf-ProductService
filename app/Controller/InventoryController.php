<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;
use App\Model\ProductionModel;
use App\Model\InventoryModel;
use App\Model\BusinessLogic\InventoryBusinessLogic;
use App\Model\BusinessLogic\HistoryBusinessLogic;
use Hyperf\DbConnection\Db;

class InventoryController extends AbstractController
{
    /**
     * [POST] /api/v1/inventory/reduceInventory
     * 減少庫存
     *
     */
    public function reduceInventory()
    {
        $p_key        = $this->request->post("p_key",null);
        $o_key        = $this->request->post("o_key",null);
        $reduceAmount = $this->request->post("reduceAmount",null);
        $type         = "create";
        
        if(is_null($p_key) || is_null($o_key) || is_null($reduceAmount) || is_null($type))  return ["status" => "fail","msg" => "請確認傳入值是否完整"];

        $productionResult = InventoryBusinessLogic::verifyProductKey($p_key);
        if(is_null($productionResult)) return ["status" => "fail","msg" => "查無此商品 key"];

        $verfiyTypeResult = HistoryBusinessLogic::verifyType($p_key,$o_key,$type);
        if($verfiyTypeResult) return ["status" => "fail","msg" => "訂單編號與類別重複，可能為重複輸入"];
        
        
        $inventoryEntity = InventoryModel::where('p_key',$p_key)->first();
        
        if(is_null($inventoryEntity)){
            return ["status" => "fail","msg" => "找不到庫存資料"];
        }

        if($inventoryEntity->amount < $reduceAmount){
            return ["status" => "fail","msg" => "庫存數量不夠"];
        }

        $inventoryModel = new InventoryModel();

        $inventoryCreatedResult = $inventoryModel->reduceInventoryTransaction($p_key, $o_key, $reduceAmount, $inventoryEntity->amount);
        if (is_null($inventoryCreatedResult)) return  ["status" => "fail","msg" => "庫存或流水帳新增失敗"];

        return [
            "msg" => "OK"
        ];
    }

    /**
     * [POST] /api/v1/inventory/addInventory
     * 庫存補償
     * 
     */
    public function addInventory()
    {
        $p_key     = $this->request->post("p_key",null);
        $o_key     = $this->request->post("o_key",null);
        $addAmount = $this->request->post("addAmount",null);
        $type      = $this->request->post("type",null);

        if(is_null($p_key) || is_null($o_key) || is_null($addAmount) || is_null($type)) return ["status" => "fail","msg" => "請確認傳入值是否完整"];

        $productionResult = InventoryBusinessLogic::verifyProductKey($p_key);
        if(is_null($productionResult)) return ["status" => "fail","msg" => "查無此商品 key"];

        $verfiyTypeResult = HistoryBusinessLogic::verifyType($p_key,$o_key,$type);
        if($verfiyTypeResult) return ["status" => "fail","msg" => "訂單編號與類別重複，可能為重複輸入"];

        $verfiyCreatedResult = HistoryBusinessLogic::verifyCreated($o_key);
        if(is_null($verfiyCreatedResult))  return ["status" => "fail","msg" => "訂單未被成立或已補償退貨"];

        $inventoryModel = new InventoryModel();
        $inventoryEntity = InventoryModel::where('p_key',$p_key)->first();
        
        if($inventoryEntity){
            $nowAmount = $inventoryEntity->amount;
        }else{
            return ["status" => "fail","msg" => "查無此訂單庫存"];
        }

        $inventoryCreatedResult = $inventoryModel->addInventoryTransaction($p_key,$o_key,$addAmount,$nowAmount,$type);
        if(!$inventoryCreatedResult) return ["status" => "fail","msg" => "庫存或流水帳新增失敗"];

        return [
            "msg" => "OK"
        ];
    }
}

?>