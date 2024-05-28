<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all users and tasks
        $users = User::all();
        $tasks = Task::all();

        // For each task, attach random users
        foreach ($tasks as $task) {
            // Get a random number of users for each task
            $randomUsers = $users->random(rand(1, 5))->pluck('id');
            $task->users()->syncWithoutDetaching($randomUsers);
        }
    }

}
