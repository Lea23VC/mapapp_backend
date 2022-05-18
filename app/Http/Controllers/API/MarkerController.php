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


class MarkerController extends BaseController
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
        return MarkerResource::collection(Marker::filter($request->all())->paginate($items_per_page));
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
        $marker = Marker::find($id);

        if (is_null($marker)) {
            return $this->sendError('marker not found.');
        }

        return $this->sendResponse(new MarkerResource($marker), 'Marker retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marker $marker)
    {
        $input = $request->all();


        //WIP validate all stuff
        // $validator = Validator::make($input, [
        //     'title' => 'required',

        // ]);

        // if ($validator->fails()) {
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }

        // $marker->title = $input['title'];
        if ($request->has("title")) {
            $marker->title = $input["title"];
        }

        if ($request->has("status")) {
            $marker->status = $input["status"];
        }

        if ($request->has("latitude")) {
            $marker->latitude = $input["latitude"];
        }

        if ($request->has("longitude")) {
            $marker->longitude = $input["longitude"];
        }

        if ($request->has("availability")) {
            $marker->availability = $input["availability"];
        }

        if ($request->has("points")) {
            $marker->points = $input["points"];
        }

        if ($request->has("PE")) {
            $marker->PE = $input["PE"];
        }

        if ($request->has("PET")) {
            $marker->PET = $input["PET"];
        }

        if ($request->has("PVC")) {
            $marker->PVC = $input["PVC"];
        }

        if ($request->has("aluminium")) {
            $marker->aluminium = $input["aluminium"];
        }

        if ($request->has("batteries")) {
            $marker->batteries = $input["batteries"];
        }

        if ($request->has("cardboard")) {
            $marker->cardboard = $input["cardboard"];
        }

        if ($request->has("cellphones")) {
            $marker->cellphones = $input["cellphones"];
        }

        if ($request->has("glass")) {
            $marker->cellphones = $input["glass"];
        }

        if ($request->has("oil")) {
            $marker->cellphones = $input["oil"];
        }

        if ($request->has("otherPapers")) {
            $marker->otherPapers = $input["otherPapers"];
        }

        if ($request->has("otherPlastics")) {
            $marker->otherPlastics = $input["otherPlastics"];
        }

        if ($request->has("paper")) {
            $marker->paper = $input["paper"];
        }

        if ($request->has("tetra")) {
            $marker->tetra = $input["tetra"];
        }




        // $marker->detail = $input['detail'];
        $marker->save();

        return $this->sendResponse(new MarkerResource($marker), 'Marker updated successfully.');
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
