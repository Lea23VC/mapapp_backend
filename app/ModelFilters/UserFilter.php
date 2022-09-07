<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];


    public function name($name): UserFilter
    {

        return $this->where(function ($q) use ($name) {
            return $q->where("name", "LIKE", "%$name%");
        });
    }

    public function email($email): UserFilter
    {

        return $this->where(function ($q) use ($email) {
            return $q->where("email", "LIKE", "%$email%");
        });
    }


    public function username($username): UserFilter
    {

        return $this->where(function ($q) use ($username) {
            return $q->where("email", "LIKE", "%$username%");
        });
    }

    public function birthAtMin($date): ShippingFilter
    {
        return $this->whereBetween("birthDate", [
            $date,
            $this->input("birthday_at_max"),
        ]);
    }

    public function minVotes($votes): ShippingFilter
    {
        return $this->where(function ($q) use ($votes) {
            return $q->where("votes", '<', $votes);
        });
    }

    public function maxVotes($votes): ShippingFilter
    {
        return $this->where(function ($q) use ($votes) {
            return $q->where("votes", '>', $votes);
        });
    }
}
