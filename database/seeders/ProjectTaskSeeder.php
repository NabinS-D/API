<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;

class ProjectTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch all projects and tasks
        $projects = Project::all();
        $tasks = Task::all();

        // For each project, attach random tasks
        foreach ($projects as $project) {
            // Get a random number of tasks for each project
            $randomTasks = $tasks->random(rand(1, 3))->pluck('task_id');
            $project->tasks()->syncWithoutDetaching($randomTasks);
        }
    }
}
