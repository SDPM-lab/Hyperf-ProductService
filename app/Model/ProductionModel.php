<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\DbConnection\Db;
use App\Utils\Log;
use Hyperf\Database\Model\SoftDeletes;

/**
 */
class ProductionModel extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'production';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['p_key','name','description','price'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];

    /**
     * 新增商品與庫存的 Transaction
     *
     * @param string $name
     * @param string $description
     * @param integer $price
     * @param integer $amount
     * @return integer|null
     */
    public function createProductionTransaction(string $name, string $description, int $price, int $amount):?int
    {
        $productionData = [
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "created_at" => date("Y-m-d H:i:s") ,
            "updated_at" => date("Y-m-d H:i:s")
        ];

        try{
            Db::beginTransaction();

            $productionInsertId = DB::table('production')->insertGetId($productionData,'p_key');

            $inventory = [
                "p_key" => $productionInsertId,
                "amount" => $amount,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];

            DB::table('inventory')->insert($inventory);

            Db::commit();
            
            return $productionInsertId;
        } catch(\Throwable $ex){
            Log::getInstance()->error('[ERROR] {exception}', ['exception' => $ex]);
            Db::rollBack();

            return null;
        }

    }
}
