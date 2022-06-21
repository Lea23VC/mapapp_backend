<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;
use App\ModelFilters\MarkerFilter;

class Marker extends Model
{
    use HasFactory, Filterable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'status', 'availability', 'imgURL', 'latitude', 'longitude', 'address_number', 'address_street', 'commune', 'city', 'state', 'country',
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
        "tetra",
        "likes",
        "dislikes"
    ];


    //relationships

    // public function address()
    // {
    //     return $this->hasOne(Address::class);
    // }

    // public function recyclableMaterial()
    // {
    //     return $this->hasOne(RecyclableMaterial::class, "marker_user");
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recyclableMaterial()
    {
        return $this->hasOne(Recyclable_Material::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }


    public function likedByUser()
    {
        return $this->belongsToMany(User::class)->withPivot('voted');
    }


    public function modelFilter(): ?string
    {
        return $this->provideFilter(MarkerFilter::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class);
    }

    public function status()
    {
        return $this->hasOne(Status::class);
    }
}
