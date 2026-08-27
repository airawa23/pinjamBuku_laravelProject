<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'rating', 'price', 'category', 'status', 'description', 'image', 'owner_id'];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
