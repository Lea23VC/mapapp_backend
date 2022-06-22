<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PermissionResource;

class UserResource extends JsonResource
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
                $image = 'https://lh5.googleusercontent.com/-b0PKyNuQv5s/AAAAAAAAAAI/AAAAAAAAAAA/AMZuuclxAM4M1SCBGAO7Rp-QP6zgBEUkOQ/s96-c/photo.jpg';
            }
        }

        return [
            'id' => $this->firebaseUID,
            'username' => $this->username,

            'likes' => $this->comment->sum('likes') + $this->marker->sum('likes'),
            'imgURL' => $image,
            'permissions' => PermissionResource::collection($this->permission()->get())
        ];
    }
}
