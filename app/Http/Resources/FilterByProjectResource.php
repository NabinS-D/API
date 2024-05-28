<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterByProjectResource extends JsonResource
{
    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

    return [
        'Project ID' => $this->project_id,
        'Project' => $this->project_name,
        'Tasks' => $this->tasks->map(function ($task) {
            return [
                'Task ID' => $task->task_id,
                'Task' => $task->task,
                'Description' => $task->description,
                'Priority' => $task->priority,
                'Files'  => $task->files->pluck('file_name')->implode(', '),
                'No of files' => $task->files->count(),
                'Users' => $task->users->pluck('name')->implode(', '),
                'No of users' => $task->users->count(),
            ];
        }),
    ];
            
    }
}
