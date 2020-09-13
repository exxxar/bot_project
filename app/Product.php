<?php

namespace App;

use App\Enums\ProductTypeEnum;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use CastsEnums;

    protected $enumCasts = [
        'type' => ProductTypeEnum::class,
    ];

    protected $casts = [
        "images" => "array"
    ];

    protected $fillable = [
        "title",
        "images",
        "price",
        "position",
        "description",
        "type",
        "created_by_id"
    ];

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }
}
