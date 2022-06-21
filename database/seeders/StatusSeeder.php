<?php

namespace Database\Seeders;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Log;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Log::info("Log inside StatusSeeder");
        $json = File::get("database/seeders/status.json");
        $data = json_decode($json);
        foreach ($data as $key => $item) {
            Log::info($item->name);
            Status::create(array(
                'name' => $item->name,
                'code' => $item->code,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ));
        }
    }
}
