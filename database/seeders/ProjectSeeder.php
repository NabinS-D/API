<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectNote;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()->count(15)->create()->each(function ($project) {
            // Create 5  ProjectNote for each Project
            ProjectNote::factory(5)->create(['project_id' => $project->project_id]);
     });
    }
}
