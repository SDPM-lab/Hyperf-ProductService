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
use Hyperf\DbConnection\Db;

class ProductionController extends AbstractController
{
    /**
     * [GET] /api/v1/products
     * 取得所有的產品清單
     *
     */
    public function index()
    {
        $limit  = $this->request->input("limit",10);
        $offset = $this->request->input("offset",0);
        $search = $this->request->input("search","");
        $isDesc = $this->request->input("isDesc","desc");

        $query = ProductionModel::query()->orderBy("p_key",$isDesc ? "desc" : "asc");

        if($search !== "") $query->where('name', 'like', "%$search%");
        $amount = $query->count('*');
        $products = $query->offset($offset)->limit($limit)->get();
        
        $data = [
            "list"   => [],
            "amount" => $amount
        ];

        if($products){
            foreach ($products as $product) {
                $productionData = [
                    "id"          => $product["p_key"],
                    "name"        => $product["name"],
                    "price"       => $product["price"],
                    "createdAt"   => $product["created_at"],
                    "updatedAt"   => $product["updated_at"]
                ];
                $data["list"][] = $productionData;
            }
        }else{
            return [
                "status" => "fail",
                "msg"    => "無資料"
            ];
        }
        

        return [
            "msg" => "OK",
            "data" => $data
        ];
    }

    public function show($id = null)
    {
        if(is_null($id)) {
            return[
                "status" => "fail",
                "msg"    => "無資料"
            ];;
        }

        $product = ProductionModel::where("p_key",$id)->first();
        $inventory = InventoryModel::where("p_key",$product["p_key"])->first();

        if($product){
            $data = [
                "p_key"       => $product["p_key"],
                "name"        => $product["name"],
                "description" => $product["description"],
                "price"       => $product["price"],
                "amount"      => $inventory["amount"],
                "createdAt"   => $product["created_at"],
                "updatedAt"   => $product["updated_at"]
            ];
        }else{
            return [
                "status" => "fail",
                "msg"    => "無資料"
            ];
        }

        return [
            "msg" => "OK",
            "data" => $data
        ];
    }

    public function create()
    {
        $name        = $this->request->post("name",null);
        $description = $this->request->post("description",null);
        $price       = $this->request->post("price",null);
        $amount      = $this->request->post("amount",null);

        if(is_null($name) || is_null($description) || is_null($price) || is_null($amount)) return ["status" => "fail","msg" => "傳入資料錯誤"];
    
        $productModel = new ProductionModel();
        $productInsertResult = $productModel->createProductionTransaction($name, $description, $price, $amount);

        if($productInsertResult){
            return [
                    "msg" => "OK",
                    "product_id" => $productInsertResult
                ];
        }else{
            return [
                "status" => "fail",
                "msg"    => "新增商品或新增庫存失敗"];
        }
    }

    public function update($id = null)
    {
        $data = $this->request->post();
        
        $name         = $data["name"]         ?? null;
        $description  = $data["description"]  ?? null;
        $price        = $data["price"]        ?? null;

        if(is_null($id)) return ["status" => "fail", "msg" => "請傳入產品key"];
        if(is_null($name) && is_null($description) && is_null($price)) return ["status" => "fail", "msg" => "請傳入更改資料"];
        
        $product = ProductionModel::where("p_key",$id)->first();
   
        if (is_null($product)) return ["status" => "fail", "msg" => "查無此商品"];

        $product["p_key"] = $id;
        if(!is_null($name))        $product["name"] = $name;
        if(!is_null($description)) $product["description"] = $description;
        if(!is_null($price))       $product["price"] = $price;
        
        ProductionModel::where('p_key',$product["p_key"])->update($product->toArray());

        return [
            "msg" => "OK"
        ];
    }

    public function delete($id = null)
    {
        
        if(is_null($id)) return ["status" => "fail", "msg" => "請傳入產品key"];

        $product = ProductionModel::where("p_key",$id)->first();

        if (is_null($product)) return ["status" => "fail", "msg" => "查無此商品"];
        
        $result = ProductionModel::where("p_key",$id)->delete();

        if ($result) {
            return [
                "msg" => "OK",
                "res" => $result
            ];
        }

        return [
            "status" => "fail",
            "msg" => "刪除商品失敗"
        ];
        
    }
}
