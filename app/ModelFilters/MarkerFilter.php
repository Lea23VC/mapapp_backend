<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class MarkerFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];


    public function title($title): MarkerFilter
    {

        return $this->where(function ($q) use ($title) {
            return $q->where("title", "LIKE", "%$title%");
        });
    }

    // public function id($id): MarkerFilter
    // {
    //     return $this->where(function ($q) use ($id) {
    //         return $q->where("id", "LIKE", "%$id%");
    //     });
    // }

    public function id($id): MarkerFilter
    {
        return $this->where(function ($q) use ($id) {
            return $q->where("id", $id);
        });
    }

    public function user_id($user_id): MarkerFilter
    {
        return $this->where(function ($q) use ($user_id) {
            return $q->where("user_id", $user_id);
        });
    }

    public function status($status): MarkerFilter
    {
        return $this->where(function ($q) use ($status) {
            return $q->where("status", "LIKE", "%$status%");
        });
    }

    public function availability($availability): MarkerFilter
    {
        return $this->where(function ($q) use ($availability) {
            return $q->where("availability", $availability);
        });
    }

    public function commune($commune): MarkerFilter
    {
        return $this->where(function ($q) use ($commune) {
            return $q->where("commune", "LIKE", "%$commune%");
        });
    }

    public function address_number($address_number): MarkerFilter
    {
        return $this->where(function ($q) use ($address_number) {
            return $q->where("address_number", "LIKE", "%$address_number%");
        });
    }

    public function address_street($address_street): MarkerFilter
    {
        return $this->where(function ($q) use ($address_street) {
            return $q->where("address_street", "LIKE", "%$address_street%");
        });
    }

    public function state($state): MarkerFilter
    {
        return $this->where(function ($q) use ($state) {
            return $q->where("state", "LIKE", "%$state%");
        });
    }

    public function country($country): MarkerFilter
    {
        return $this->where(function ($q) use ($country) {
            return $q->where("country", "LIKE", "%$country%");
        });
    }


    public function PE($pe): MarkerFilter
    {
        return $this->where(function ($q) use ($pe) {
            return $q->where("PE", $pe);
        });
    }


    public function PET($PET): MarkerFilter
    {
        return $this->where(function ($q) use ($PET) {
            return $q->where("PET", $PET);
        });
    }

    public function PVC($PVC): MarkerFilter
    {
        return $this->where(function ($q) use ($PVC) {
            return $q->where("PVC", $PVC);
        });
    }

    public function aluminium($aluminium): MarkerFilter
    {
        return $this->where(function ($q) use ($aluminium) {
            return $q->where("aluminium", $aluminium);
        });
    }

    public function batteries($batteries): MarkerFilter
    {
        return $this->where(function ($q) use ($batteries) {
            return $q->where("batteries", $batteries);
        });
    }

    public function cardboard($cardboard): MarkerFilter
    {
        return $this->where(function ($q) use ($cardboard) {
            return $q->where("cardboard", $cardboard);
        });
    }
    public function glass($glass): MarkerFilter
    {
        return $this->where(function ($q) use ($glass) {
            return $q->where("glass", $glass);
        });
    }
    public function oil($oil): MarkerFilter
    {
        return $this->where(function ($q) use ($oil) {
            return $q->where("oil", $oil);
        });
    }
    public function otherPapers($otherPapers): MarkerFilter
    {
        return $this->where(function ($q) use ($otherPapers) {
            return $q->where("otherPapers", $otherPapers);
        });
    }

    public function otherPlastics($otherPlastics): MarkerFilter
    {
        return $this->where(function ($q) use ($otherPlastics) {
            return $q->where("otherPlastics", $otherPlastics);
        });
    }

    public function paper($paper): MarkerFilter
    {
        return $this->where(function ($q) use ($paper) {
            return $q->where("paper", $paper);
        });
    }

    public function tetra($tetra): MarkerFilter
    {
        return $this->where(function ($q) use ($tetra) {
            return $q->where("tetra", $tetra);
        });
    }
}
