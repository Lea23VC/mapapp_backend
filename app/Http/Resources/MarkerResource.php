<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CommentResource;
use Log;
use App\Http\Resources\UserResource;
use Auth;

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

        $input = $request->all();
        $image = null;
        $id = Auth::user()->id;
        Log::info("AAAAAAAAAAAAAAAAA");
        Log::info($request);
        Log::info($id);
        if ($this->imgURL) {
            $expiresAt = new \DateTime('tomorrow');
            $imageReference = app('firebase.storage')->getBucket()->object($this->imgURL);

            if ($imageReference->exists()) {
                $image = $imageReference->signedUrl($expiresAt);
            } else {
                $image = null;
            }
        }

        $voted_marker = $this->likedByUser()->where('user_id', $id)->first();

        $distance = 0;
        if ($request->has('distanceFromCoords')) {
            $coords = json_decode($request->input('distanceFromCoords'));
            $distance = (((acos(sin(($coords[0] * pi() / 180)) * sin(($this->latitude * pi() / 180)) + cos(($coords[0] * pi() / 180)) * cos(($this->latitude * pi() / 180)) * cos((($coords[1] - $this->longitude) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515 * 1.609344);
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
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
            'comments' =>  CommentResource::collection(($this->comment()->latest()->get())),
            'user' => UserResource::collection(($this->user()->get())),
            "voted_marker" =>  $voted_marker != null ? $voted_marker->pivot->voted : 0,
        ];
    }
}
