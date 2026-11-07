<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'rarity',
        'equipment_category',
        'image_url',
        'source_url',
    ];
}
