<?php

namespace Database\Seeders;

use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $json = File::get("database/seeders/material.json");
        $data = json_decode($json);
        foreach ($data as $key => $item) {
            Material::create(array(
                'name' => $item->name,
                'code' => $item->code,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ));
        }
    }
}
