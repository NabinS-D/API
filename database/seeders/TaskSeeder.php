<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Note;
use App\Models\File;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Task::factory()->count(15)
                      ->create()->each(function (Task $task) 
                      {
                        Note::factory(4)->create(['task_id' => $task->task_id]);
                        File::factory(3)->create(['task_id'=> $task->task_id]);

                          // Create the User instances
                          $users = User::factory(3)->create();

                          // Attach the users to the task
                          foreach ($users as $user) {
                              $task->users()->attach($user);
                          }
                      });
                      
    }
}
