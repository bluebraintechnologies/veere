<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deals extends Model
{
    protected $table = 'deals';
    protected $dates = ['starts_at', 'ends_at'];
    protected $guarded = ['id'];
    
    public function variations()
    {
        return $this->belongsToMany(\App\Variation::class, 'discount_variations', 'discount_id', 'variation_id');
    }
}
