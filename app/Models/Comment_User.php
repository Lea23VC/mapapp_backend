<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User_Role extends Pivot
{
    public function tag(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tag::class, 'tag_id');
    }
}
