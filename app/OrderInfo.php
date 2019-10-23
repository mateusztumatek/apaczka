<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    protected $fillable = ['order_id', 'is_send'];
    protected $connection = 'mysql';
}
