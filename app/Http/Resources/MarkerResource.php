<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address_street . " " . $this->address_number . ", " . $this->commune . ", " . $this->city . ", " . $this->state . ", " . $this->country,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
