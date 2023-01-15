<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;
    protected $table = 'product_reviews';
    protected $guarded = [];    
    public function contact(){
        return $this->belongsTo('App\Models\Contact', 'contact_id');
    }
}
