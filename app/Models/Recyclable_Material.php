<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recyclable_Material extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array  
     */
    protected $fillable = [
        'PE',
        'PET',
        'PVC',
        "aluminium",
        "batteries",
        "cardboard",
        "cellphones",
        "glass",
        "oil",
        "otherPapers",
        "otherPlastics",
        "paper",
        "tetra"
    ];
}
