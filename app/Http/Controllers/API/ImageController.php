<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Google\Cloud\Firestore\FirestoreClient;
use Session;
use Illuminate\Support\Str;
use Image;
use App\Jobs\UploadImage;

class ImageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $expiresAt = new \DateTime('tomorrow');
        $imageReference = app('firebase.storage')->getBucket()->object("Images/defT5uT7SDu9K5RFtIdl.png");

        if ($imageReference->exists()) {
            $image = $imageReference->signedUrl($expiresAt);
        } else {
            $image = null;
        }

        return view('img', compact('image'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'image' => 'required',
        ]);
        $input = $request->all();
        // $image = $request->file('image'); //image file from frontend

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = 'tmp/' . $filename;
            Image::make($image->getRealPath())->resize(300, 300)->save($path);
            $request->replace(['image' => $path]);
        }

        UploadImage::dispatch($path, $filename,);
        // $image = $request->file('image'); //image file from frontend

        // $student   = app('firebase.firestore')->database()->collection('Images')->document(Str::uuid());
        // $firebase_storage_path = 'Images/';
        // $name     = $student->id();
        // $localfolder = public_path('firebase-temp-uploads') . '/';
        // $extension = $image->getClientOriginalExtension();
        // $file      = $name . '.' . $extension;
        // if ($image->move($localfolder, $file)) {
        //     $uploadedfile = fopen($localfolder . $file, 'r');
        //     app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
        //     //will remove from local laravel folder
        //     unlink($localfolder . $file);
        //     Session::flash('message', 'Succesfully Uploaded');
        // }
        return $this->sendResponse($input, 'Image uploaded successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $imageDeleted = app('firebase.storage')->getBucket()->object("Images/defT5uT7SDu9K5RFtIdl.png")->delete();
        Session::flash('message', 'Image Deleted');
        return back()->withInput();
    }
}
