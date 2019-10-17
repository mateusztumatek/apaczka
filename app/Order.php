<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['country', 'addr_xycache', 'shipping', 'status', 'notes'];
    protected $table = 'orders';
    protected $connection = 'external_db';

    public function address(){
        return $this->belongsTo('App\Address', 'addr_xycache');
    }
    public function shipping_relation(){
        return $this->belongsTo('App\Shippment', 'shipping')->with('shippment_maps');
    }
}
