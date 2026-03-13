<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['book_name','year','category_id','image_path'];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function lends(){
        return $this->hasMany(Lend::class, 'book_id');
    }
}
