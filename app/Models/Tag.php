<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'x1',
        'x2',
        'y1',
        'y2',
        'details',
        'image_id',
    ];
}
