<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Task ID' => $this->task_id,
            'Task' => $this->task,
            'Description' => $this->description,
            'Priority' => $this->priority,
            'Files'  => $this->files->pluck('file_name')->implode(', '),
            'No of files' => $this->files->count(),
            'Projects' => $this->projects->pluck('project_name')->implode(', '),
            'Project IDs' => $this->projects->pluck('project_id')->implode(', '),
            'No of projects' => $this->projects->count(),
            'Users' => $this->users->pluck('name')->implode(', '),
            'User Ids'=> $this->users->pluck('id')->implode(', ')
        ];
    }
}
