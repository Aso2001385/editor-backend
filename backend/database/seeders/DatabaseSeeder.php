<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Project;
use App\Models\Design;
use App\Models\ProjectUser;
use App\Models\ProjectDesign;
use App\Models\UserDesign;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {

        /*
        $users = User::factory(5)
            ->has(Project::factory(3)
            ->has(Design::factory(3)),'projects','designs')
            ->create();
        */

        /*
        $users = User::factory(5)
            ->has(Design::factory(3),'designs')
            ->create();
        */

        /*
        $users = User::factory(5)
            ->has(Project::factory(3)
            ->has(Design::factory(3))
            ->state( function(array $attributes, User $user) {
                    return ['id' => $user->type];
            }))
            ->create();
        */

        /*
        $users = User::factory(5)
            ->has(Design::factory(3)
            ->state(function(array $attributes, User $user) {
                return ['id' => $user->type];
            }))
            ->create();
        */

        /*
        $users = User::factory(10)->has(Project::factory())
            ->has(Design::factory())
            ->create()->each(function($user){
                $user = Project::factory()->create(['user_id'=>$user->id]);
                $user = Design::factory()->create(['user_id'=>$user->id]);
            });
        */

        $users=User::factory(10)->create();

        foreach($users as $user){

            $project = Project::create([
                'user_id' => $user['id'],
                'name'=> Str::random(5),
                'ui' => json_encode(Str::random(10))
            ]);

            $design = Design::create([
                'user_id' => $user['id'],
                'name' => Str::random(5),
                'contents' => json_encode(Str::random(10)),
                'point' => rand(0,3)
            ]);

            ProjectUser::create([
                'project_id'=>$project['id'],
                'user_id'=>$user['id']
            ]);

            ProjectDesign::create([
                'project_id'=>$project['id'],
                'design_id'=>$design['id'],
                'class'=>1
            ]);

            UserDesign::create([
                'user_id'=>$user['id'],
                'design_id'=>$design['id'],
            ]);

        }

    }
}
