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
        return Marker::filter($request->all())->paginate($items_per_page);
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

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // $geocodertest = json_decode(app('geocoder')->reverse(-33.485090, -70.640190)->toJson(), true);
        // Log::info($geocodertest["properties"]["streetName"]);

        $user = User::find($request->json()->all()["userId"]);
        if ($user) {
            $marker = Marker::create($input);
            $user->marker()->save($marker);
            AddAddressFromCoords::dispatch($marker->id, $marker->latitude, $marker->longitude);
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

        $validator = Validator::make($input, [
            'title' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $marker->title = $input['name'];
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
