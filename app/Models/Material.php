<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [

        'name', 'code', 'icon'
    ];

    public function marker()
    {
        return $this->belongsToMany(Marker::class);
    }
}
