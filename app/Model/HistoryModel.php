<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\Database\Model\SoftDeletes;

/**
 */
class HistoryModel extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'history';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['p_key','o_key','amount','type'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];
}
