<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;
use App\ModelFilters\CommentFilter;

class Comment extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'message',
        'user_id',
        'marker_id',
        'votes',
        'id'
    ];


    public function marker()
    {
        return $this->belongsTo(Marker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function modelFilter(): ?string
    {
        return $this->provideFilter(CommentFilter::class);
    }
}
