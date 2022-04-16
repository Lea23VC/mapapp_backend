<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Google\Cloud\Firestore\FirestoreClient;
use Session;
use Illuminate\Support\Str;

use Log;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     * 
     * 
     */

    protected $path;
    protected $filename;
    public function __construct($path, $filename)
    {
        //
        $this->path = $path;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        Log::info($this->path);
        // $student   = app('firebase.firestore')->database()->collection('Images')->document(Str::uuid());
        $firebase_storage_path = 'Images/';

        $uploadedfile = fopen($this->path, 'r');
        app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $this->filename]);
        //will remove from local laravel folder
        unlink($this->path);
        // Session::flash('message', 'Succesfully Uploaded');
        // }

        Log::info("Image uploaded");
    }
}
