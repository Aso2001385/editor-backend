<?php

namespace Database\Seeders;

use App\Commons\DesignCommon;
use App\Models\Design;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FirstSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => config('master.name'),
            'email' => config('master.email'),
            'password' => Hash::make(config('master.password')),
            'class' => 1,
        ]);

        $design_uuid = (string) Str::uuid();

        $design = Design::create([
            'user_id' => $user->id,
            'uuid' => $design_uuid,
            'name' => 'DefaultDesign',
            'contents' => DesignCommon::design(),
        ]);

        Storage::put('previews/designs/'.$design->uuid.'.txt', Storage::get('previews/templates/preset.txt'));

    }
}
