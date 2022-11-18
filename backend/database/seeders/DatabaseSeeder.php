<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Design;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {

   // $user = User::factory(5)
   // ->has(Project::factory(3)
   // ->has(Design::factory(3)),'projects','designs')
    //->create();

    // $user = User::factory(5)
    // ->has(Design::factory(3),'designs')
    // ->create();

 //   $user = User::factory(5)
  //  ->has(Project::factory(3)
  //  ->has(Design::factory(3))
 //   ->state(
 //       function(array $attributes, User $user) {
 //           return ['id' => $user->type];
 //       }))
  //      ->create();

//$user = User::factory(5)
  //  ->has(Design::factory(3)
  //  ->state(
   //     function(array $attributes, User $user) {
   //         return ['id' => $user->type];
   //     }))
   // ->create();

    factory(User::class,10)->create()->each(function($user){
        factory(Project::class)->create([
            'user_id'=>$user->id

        ]);
        factory(Design::class)->create([
            'user_id'=>$user->id

        ]);
    });




    }
}
