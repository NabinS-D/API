<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $Files = [];

        foreach ($this->tasks as $task) 
        {
            foreach ($task->files as $file) 
            {
                $Files[] = $file->file_name;
            }
        }

        return [
            'Project ID' => $this->project_id,
            'Project' => $this->project_name,
            'Description' => $this->description,
            'Tasks' => $this->tasks->pluck('task')->implode(', '),
            'No of tasks' => $this->tasks->count(),
            'Files' => implode(', ', $Files),
            'No of files' => count($Files),
           
        ];
    }
}
