<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getRouteKeyName()
{
    return 'slug';
}
}
