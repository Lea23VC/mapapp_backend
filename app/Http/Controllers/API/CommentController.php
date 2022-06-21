<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Validator;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\CommentResource;
use Log;
use App\Models\User;
use App\Models\Marker;
use App\Jobs\AddAddressFromCoords;

use App\Jobs\AddPermissionToUser;


class CommentController extends BaseController
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
        return CommentResource::collection(Comment::filter($request->all())->paginate($items_per_page));
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
        $items_per_page = $request->input("items_per_page");
        // Log::info($request->json()->all()["data"]);

        $validator = Validator::make($input, [
            'message' => 'required',
            'user_id' => 'required',
            'marker_id' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // $geocodertest = json_decode(app('geocoder')->reverse(-33.485090, -70.640190)->toJson(), true);
        // Log::info($geocodertest["properties"]["streetName"]);
        $user = User::where('firebaseUID', $input["user_id"])->first();

        Log::info("Marker-Id: " . $input["marker_id"]);
        $marker = Marker::find($input["marker_id"]);
        if ($user && $marker) {
            $comment = Comment::create($request->except('marker_id', 'user_id'));
            $user->comment()->save($comment);
            $marker->comment()->save($comment);
            return $this->sendResponse(CommentResource::collection(Comment::where("marker_id", $marker->id)->latest()->paginate($items_per_page)), 'Comment created successfully.');
        } else {
            return $this->sendError('User or marker not found');
        }
        // if ($user) {
        //     $Comment = Comment::create($input);
        //     $user->Comment()->save($Comment);
        //     AddAddressFromCoords::dispatch($Comment->id, $Comment->latitude, $Comment->longitude);
        //     return $this->sendResponse(new CommentResource($Comment), 'Comment created successfully.');
        // } else {
        //     return $this->sendError('User not found');
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::find($id);

        if (is_null($comment)) {
            return $this->sendError('Comment not found.');
        }

        return $this->sendResponse(new CommentResource($comment), 'Comment retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $input = $request->all();


        //WIP validate all stuff
        // $validator = Validator::make($input, [
        //     'title' => 'required',

        // ]);

        // if ($validator->fails()) {
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }

        // $Comment->title = $input['title'];
        if ($request->has("user_id")) {
            $comment->user_id = $input["user_id"];
        }

        if ($request->has("message")) {
            $comment->message = $input["message"];
        }

        if ($request->has("likes") && $request->has("dislikes") && $request->has("user_voted") && $request->has("vote_action")) {

            $user = User::where('firebaseUID', $input["user_voted"])->first();

            if ($user) {
                Log::info("FOUND!!");
                $comment->likes = $input["likes"];
                $comment->dislikes = $input["dislikes"];

                $user_maker = $comment->likedByUser()->where('user_id', $user->id)->first();

                if (!$user_maker) {
                    $comment->likedByUser()->attach($user, array('voted' => $input["vote_action"]));
                } else {
                    $comment->likedByUser()->updateExistingPivot($user, array('voted' => $input["vote_action"]));
                }

                AddPermissionToUser::dispatch($comment->user);
            }
        }





        // $Comment->detail = $input['detail'];
        $comment->save();

        return $this->sendResponse(new CommentResource($comment), 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return $this->sendResponse([], 'Comment deleted successfully.');
    }
}
