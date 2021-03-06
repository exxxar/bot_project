<?php

namespace Laravel\BotCashBack\Models;

use Illuminate\Database\Eloquent\Model;

class CashBackHistory extends Model
{
    //
    protected $fillable = [
        'amount',
        'bill_number',
        'money_in_bill',
        'employee_id',
        'bot_user_info_id',
        'type',
    ];
}
