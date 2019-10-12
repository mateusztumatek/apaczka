<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['name', 'street', 'postal', 'city', 'phone', 'email'];
    protected $table = 'addr';
    protected $connection = 'external_db';
}
