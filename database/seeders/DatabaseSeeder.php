<?php

namespace Database\Seeders;

use App\Models\ProjectNote;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\TaskUser;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            "name"=> "Nabin",
            "email"=> "Nabin@gmail.com",
            "password" => bcrypt("Nabin@123"),
        ]);
        $this->call([
            ProjectSeeder::class,
            TaskSeeder::class,
            ProjectTaskSeeder::class,
            TaskUserSeeder::class,  
        ]);
    
      
    }
}
