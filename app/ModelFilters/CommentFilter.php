<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CommentFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];


    public function message($message): CommentFilter
    {

        return $this->where(function ($q) use ($message) {
            return $q->where("message", "LIKE", "%$message%");
        });
    }

    // public function id($id): CommentFilter
    // {
    //     return $this->where(function ($q) use ($id) {
    //         return $q->where("id", "LIKE", "%$id%");
    //     });
    // }

    public function id($id): CommentFilter
    {
        return $this->where(function ($q) use ($id) {
            return $q->where("id", $id);
        });
    }

    public function user_id($user_id): CommentFilter
    {
        return $this->where(function ($q) use ($user_id) {
            return $q->where("user_id", $user_id);
        });
    }


    public function marker_id($marker_id): CommentFilter
    {
        return $this->where(function ($q) use ($marker_id) {
            return $q->where("marker_id", $marker_id);
        });
    }

    public function votes($votes): CommentFilter
    {
        return $this->where(function ($q) use ($votes) {
            return $q->where("votes", $votes);
        });
    }
}
