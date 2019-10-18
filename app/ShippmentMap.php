<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippmentMap extends Model
{
    protected $fillable = ['apaczka_codename', 'order_type', 'is_domestic', 'pickup', 'is_paczkomat', 'cash_on_delivery'];
    protected $table = 'shippment_maps';
    protected $connection = 'mysql';
}
