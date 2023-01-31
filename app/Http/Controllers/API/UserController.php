<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marker;
use Validator;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\MarkerResource;


use Log;
use App\Models\User;
use App\Jobs\AddAddressFromCoords;
use Illuminate\Support\Str;
use Image;
use App\Jobs\UploadImage;

use App\Http\Resources\UserResource;



class UserController extends BaseController
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items_per_page = $request->input("items_per_page");


        return UserResource::collection(User::filter($request->all())->paginate(10))->sortBy(['likes', 'desc']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        // Log::info($request->json()->all()["data"]);

        $validator = Validator::make($input, [
            'title' => 'required',
            'image' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // $geocodertest = json_decode(app('geocoder')->reverse(-33.485090, -70.640190)->toJson(), true);
        // Log::info($geocodertest["properties"]["streetName"]);

        $user = User::find($request->all()["userId"]);
        if ($user) {
            $marker = Marker::create($input);
            $user->marker()->save($marker);
            AddAddressFromCoords::dispatch($marker->id, $marker->latitude, $marker->longitude);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $path = 'tmp/' . $filename;
                Image::make($image->getRealPath())->save($path);
                $request->replace(['image' => $path]);
                Log::info("ID: " . $marker->id);
                UploadImage::dispatch($path, $filename, $marker->id);
            }


            return $this->sendResponse(new MarkerResource($marker), 'Marker created successfully.');
        } else {
            return $this->sendError('User not found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        $user = User::where('firebaseUID', $id)->first();;

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $user = User::where('firebaseUID', $id)->first();;

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }



        if ($request->has("username")) {
            $user->name = $input["username"];
        }

        if ($request->hasFile('image')) {
            Log::info("ID 2: " . $user->id);
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = 'tmp/' . $filename;
            Image::make($image->getRealPath())->save($path);
            $request->replace(['image' => $path]);
            Log::info("ID: " . $user->id);
            UploadImage::dispatch($path, $filename, $user->id, $user);
        }


        // $marker->detail = $input['detail'];
        $user->save();

        return $this->sendResponse(new UserResource($user), 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marker $marker)
    {
        $marker->delete();

        return $this->sendResponse([], 'Marker deleted successfully.');
    }
}
