<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //

    protected $fillable = [
        'full_name',
        'phone',
        'height',
        'age',
        'birth_month',
        'birth_day',
        'sex',
        'model_school_education',
        'wish_learn',
        'wish_photoproject',
        'city',
        "user_id"
    ];


    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
