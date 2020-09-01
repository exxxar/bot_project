<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        "question",
        "media",
        "options",
        "is_anonymous",
        "allows_multiple_answers",
        "correct_option_id",
        "close_date",
        "type",
        "created_by_id"
    ];

    protected $casts = [
        "media" => "array",
        "options" => "array"
    ];
}
