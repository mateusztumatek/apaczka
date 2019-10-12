<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['country', 'addr_xycache', 'shipping', 'status'];
    protected $table = 'orders';
    protected $connection = 'external_db';

    public function address(){
        return $this->belongsTo('App\Address', 'addr_xycache');
    }
    public function shipping(){
        return $this->belongsTo('App\Shippment', 'shipping');
    }
}
