<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marker;
use Validator;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\MarkerResource;
use App\Http\Resources\MarkerResourceCoords;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\StatusResource;


use Log;
use App\Models\User;
use App\Models\Material;
use App\Models\Permission;
use App\Models\Status;

use App\Jobs\AddAddressFromCoords;
use Illuminate\Support\Str;
use Image;
use App\Jobs\UploadImage;

use App\Jobs\AddPermissionToUser;

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
        //Move this to filter someday
        // if ($request->has("latitude") && $request->has("longitude")) {

        //     $lat = $request->input('latitude');
        //     $lon = $request->input('longitude');
        //     $distance = 150;
        //     $aa =  (
        //         (
        //             (acos(
        //                 sin(($lat * pi() / 180))
        //                     *
        //                     sin((`latitude` * pi() / 180)) + cos(($lat * pi() / 180))
        //                     *
        //                     cos((`latitude` * pi() / 180)) * cos((($lon - `longitude`) * pi() / 180))
        //             )
        //             ) * 180 / pi()
        //         ) * 60 * 1.1515 * 1.609344
        //     );
        //     return DB::table('markers')->selectRaw('(((acos(sin(( -33.483605 * pi() / 180))*sin(( `latitude` * pi() / 180)) + cos(( -33.483605 * pi() /180 ))*cos(( `latitude` * pi() / 180)) * cos((( -70.6354267 - `longitude`) * pi()/180)))) * 180/pi()) * 60 * 1.1515 * 1.609344) as distance')->selectRaw('markers.*')->get();
        // }



        if ($request->has("distanceFromCoords")) {
            $coords = json_decode($request->input('distanceFromCoords'));
            return MarkerResourceCoords::collection(Marker::filter($request->all())->selectRaw('*,(((acos(sin((' . $coords[0] . '* pi() / 180)) * sin((`latitude` * pi() / 180)) + cos((' . $coords[0] . '* pi() / 180)) * cos((`latitude` * pi() / 180)) * cos(((' . $coords[1] . ' - `longitude`) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515 * 1.609344) as distance')->orderBy('distance', 'asc')->where('distance', '<', 5)->paginate($items_per_page));
        } else {
            return MarkerResourceCoords::collection(Marker::filter($request->all())->paginate($items_per_page));
        }
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

        Log::info("Recyclable materials");
        Log::info($input);
        $validator = Validator::make($input, [
            'title' => 'required',
            'image' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // $geocodertest = json_decode(app('geocoder')->reverse(-33.485090, -70.640190)->toJson(), true);
        // Log::info($geocodertest["properties"]["streetName"]);

        $user = User::where('firebaseUID', $input["userId"])->first();;
        if ($user) {
            $marker = Marker::create($input);
            $user->marker()->save($marker);

            //descomentar cuando Google ya no quiera cortar mi cabeza
            // AddAddressFromCoords::dispatch($marker->id, $marker->latitude, $marker->longitude);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $path = 'tmp/' . $filename;
                Image::make($image->getRealPath())->save($path);
                $request->replace(['image' => $path]);
                Log::info("ID: " . $marker->id);
                UploadImage::dispatch($path, $filename, $marker->id, $marker);
            }


            $marker->materials()->sync([]);
            $materials = json_decode($input['recyclableMaterials']);

            foreach ($materials as $materialInput) {
                $material = Material::where("code", $materialInput->code)->first();
                Log::info("Material: ");
                Log::info($material);
                Log::info("Material input: ");
                Log::info($materialInput->value);
                if ($materialInput->value) {
                    $marker->materials()->syncWithoutDetaching($material);
                }
            }



            $marker->status()->associate(Status::where("code", $input['status'])->first());


            $marker->save();

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
    public function show(Request $request, $id)


    {


        $marker = Marker::find($id);

        if (is_null($marker)) {
            return $this->sendError('marker not found.');
        }

        if ($request->has("distanceFromCoords")) {

            return $this->sendResponse(new MarkerResource($marker), 'Marker retrieved successfully.');
        } else {
            return $this->sendResponse(new MarkerResource($marker), 'Marker retrieved successfully.');
        }
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
            $marker->status()->associate(Status::where("code", $input['status'])->first());
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

        if ($request->has("likes") && $request->has("dislikes") && $request->has("user_voted") && $request->has("vote_action")) {

            $user = User::where('firebaseUID', $input["user_voted"])->first();

            if ($user) {
                Log::info("FOUND!!");
                $marker->likes = $input["likes"];
                $marker->dislikes = $input["dislikes"];

                $user_maker = $marker->likedByUser()->where('user_id', $user->id)->first();

                if (!$user_maker) {
                    $marker->likedByUser()->attach($user, array('voted' => $input["vote_action"]));
                } else {
                    $marker->likedByUser()->updateExistingPivot($user, array('voted' => $input["vote_action"]));
                }

                AddPermissionToUser::dispatch($marker->user);
            }
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


        if ($request->has("recyclableMaterials")) {
            $marker->materials()->sync([]);
            $materials = json_decode($input['recyclableMaterials']);

            foreach ($materials as $materialInput) {
                $material = Material::where("code", $materialInput->code)->first();
                Log::info("Material: ");
                Log::info($material);
                Log::info("Material input: ");
                Log::info($materialInput->value);
                if ($materialInput->value) {
                    $marker->materials()->syncWithoutDetaching($material);
                }
            }
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = 'tmp/' . $filename;
            Image::make($image->getRealPath())->save($path);
            $request->replace(['image' => $path]);
            Log::info("ID: " . $marker->id);
            UploadImage::dispatch($path, $filename, $marker->id, $marker);
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

    public function getAllStatus()
    {
        $status = Status::all();
        return $this->sendResponse(StatusResource::collection($status), 'Status retrieved successfully.');
    }

    public function getAllPermissions()
    {
        $permissions = Permission::all();
        return $this->sendResponse(PermissionResource::collection($permissions), 'Status retrieved successfully.');
    }
}
