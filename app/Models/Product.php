<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [ 'name' , 'description' , 'quantity' , 'price' , 'image' , 'user_id' ];

    public function user() {
        return $this->belongsTo('App\Models\User' , 'user_id' , 'id');
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'products_categories');
    }
}
