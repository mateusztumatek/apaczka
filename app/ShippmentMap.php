<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippmentMap extends Model
{
    protected $fillable = ['apaczka_codename', 'order_type'];
    protected $table = 'shippment_maps';
    protected $connection = 'mysql';
}
