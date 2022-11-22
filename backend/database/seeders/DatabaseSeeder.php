<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Project;
use App\Models\Design;
use App\Models\ProjectDesign;

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

    // $user = User::factory(10)->has(Project::factory())
    // ->has(Design::factory())
    // ->create()->each(function($user){
    // $user = Project::factory()->create([
    //         'user_id'=>$user->id,
    //     ]);

    //     $user = Design::factory()->create([
    //         'user_id'=>$user->id
    //     ]);

    // });

        $users=User::factory(10)->create();

        foreach($users as $user){

            $project_info=[
                'name'=> Str::random(5),
                'user_id'=>$user['id']
            ];
            $projects=Project::create($project_info);

            $design_info=[
                'name'=>Str::random(5),
                'user_id'=>$user['id']

            ];
            $designs=Design::create($design_info);



        }



    }
}
