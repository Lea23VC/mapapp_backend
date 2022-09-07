<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Marker;
use Log;

class AddAddressFromCoords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $latitude;
    protected $longitude;
    protected $id;

    public function __construct($id, $latitude, $longitude)
    {
        //
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $geocoder = json_decode(app('geocoder')->reverse($this->latitude, $this->longitude)->toJson(), true);
        Log::info($geocoder["properties"]["streetName"]);
        $marker = Marker::find($this->id);
        $marker->address_number = $geocoder["properties"]["streetNumber"];
        $marker->address_street = $geocoder["properties"]["streetName"];
        $marker->commune = $geocoder["properties"]["adminLevels"][3]["name"];
        $marker->city = $geocoder["properties"]["adminLevels"][2]["name"];
        $marker->state = $geocoder["properties"]["adminLevels"][1]["name"];
        $marker->country = $geocoder["properties"]["country"];
        $marker->save();
    }
}
