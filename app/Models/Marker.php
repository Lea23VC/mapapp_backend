<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'status', 'availability'
    ];


    //relationships

    public function address()
    {
        return $this->hasOne(Address::class);
    }

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
        return $this->hasOne(Comment::class);
    }
}
