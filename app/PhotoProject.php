<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoProject extends Model
{
    protected $fillable = [
        "title",
        "image",
        "date",
        "time",
        "place",
        "photographer",
        "teacher",
        "sponsor",
        "price",
        "is_active",
        "position",
        "created_by_id"
    ];
}
