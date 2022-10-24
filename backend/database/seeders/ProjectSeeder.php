<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('projects')->insert([
            'user_id' => rand(1,10),
            'name' => Str::random(10),
            'updated_at' => Carbon::date('Y-m-d H:i:s'),
            'created_at' => Carbon::date('Y-m-d H:i:s'),
        ]);

    }
}
