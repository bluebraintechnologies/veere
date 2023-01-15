<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutomerCart extends Model
{
    use HasFactory;
    protected $table = 'customer_carts';
    protected $guarded = [];
}
