<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shippment extends Model
{
    protected $fillable = ['name', 'price'];
    protected $connection = 'external_db';
    protected $table = 'shipment';
    public function shippment_maps(){
        return $this->hasOne('App\ShippmentMap', 'order_type');
    }
}
