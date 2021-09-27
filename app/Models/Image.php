<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [

    ];

    /**
     * Owner of the Image
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tags in an Image
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
