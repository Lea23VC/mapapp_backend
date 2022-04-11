<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markers extends Model
{
    use HasFactory;


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
        return $this->hasOne(User::class);
    }
}
