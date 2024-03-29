<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use EloquentFilter\Filterable;
use App\ModelFilters\UserFilter;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        "firebaseUID",
        "birthDate",
        'profilePic'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function marker(): HasMany
    {
        return $this->hasMany(Marker::class);
    }
    public function permission()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function modelFilter(): ?string
    {
        return $this->provideFilter(UserFilter::class);
    }

    public function votedMarkers()
    {
        return $this->BelongsToMany(Marker::class)->withPivot('voted');
    }

    public function votedComments()
    {
        return $this->BelongsToMany(Comment::class)->withPivot('voted');
    }


    #for graphql

    public function markers(): HasMany
    {
        return $this->hasMany(Marker::class);
    }
}
