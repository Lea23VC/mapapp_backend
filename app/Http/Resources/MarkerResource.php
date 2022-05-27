<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CommentResource;
use Log;
use App\Http\Resources\UserResource;

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
        Log::info("AAAAAAAAAAAAAAAAA");
        Log::info($request);
        if ($this->imgURL) {
            $expiresAt = new \DateTime('tomorrow');
            $imageReference = app('firebase.storage')->getBucket()->object($this->imgURL);

            if ($imageReference->exists()) {
                $image = $imageReference->signedUrl($expiresAt);
            } else {
                $image = null;
            }
        }

        $distance =  (((acos(sin((-33.483605 * pi() / 180)) * sin(($this->latitude * pi() / 180)) + cos((-33.483605 * pi() / 180)) * cos(($this->latitude * pi() / 180)) * cos(((-70.6354267 - $this->longitude) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515 * 1.609344) * 1000;
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'points' => $this->points,
            'distance' =>  round($distance, 2) . ' metros',
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
            'comments' =>  CommentResource::collection(($this->comment()->get())),
            'user' => UserResource::collection(($this->user()->get())),
        ];
    }
}
