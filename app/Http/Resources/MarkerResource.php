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
        $image = null;

        if ($this->imgURL) {
            $expiresAt = new \DateTime('tomorrow');
            $imageReference = app('firebase.storage')->getBucket()->object($this->imgURL);

            if ($imageReference->exists()) {
                $image = $imageReference->signedUrl($expiresAt);
            } else {
                $image = null;
            }
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            "availability" => $this->availability,
            'imgURL' => $image,
            'address' => $this->address_street . " " . $this->address_number . ", " . $this->commune . ", " . $this->city . ", " . $this->state . ", " . $this->country,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'PE' => $this->PE,
            'PET' => $this->PET,
            'PVC' => $this->PVC,
            'aluminum' => $this->aluminum,
            'batteries' => $this->batteries,
            'cardboard' => $this->cardboard,
            'cellphones' => $this->cellphones,
            'glass' => $this->glass,
            'oil' => $this->oil,
            'otherPapers' => $this->otherPapers,
            'otherPlastics' => $this->otherPlastics,
            'paper' => $this->paper,
            'tetra' => $this->tetra,
            'comments' => $this->comment()->get(),
        ];
    }
}
