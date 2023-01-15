<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferredDeliveryTime extends Model
{
    use HasFactory;
    protected $table = 'preferred_delivery_time';
    protected $guarded = [];
}
