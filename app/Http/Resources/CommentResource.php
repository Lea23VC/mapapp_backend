<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use Auth;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $id = Auth::user()->id;
        $voted_marker = $this->likedByUser()->where('user_id', $id)->first();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'marker_id' => $this->marker_id,
            'message' => $this->message,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            // 'longitude' => $this->longitude,
            // "availability" => $this->availability,
            // 'address' => $this->address_street . " " . $this->address_number . ", " . $this->commune . ", " . $this->city . ", " . $this->state . ", " . $this->country,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
            'user' => UserResource::collection($this->user()->get()),
            "voted_marker" =>  $voted_marker != null ? $voted_marker->pivot->voted : 0,

        ];
    }
}
