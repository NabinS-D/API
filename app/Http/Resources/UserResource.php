<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {    
        $files = []; 
        foreach($this->tasks as $task)
        {  
            foreach($task->files as $file)
            {
                $files[] = $file->file_name;
            }
          
        }
    
        return [
            'User ID' => $this->id,
            'User Name'=> $this->name,
            'Assigned Task IDs' => $this->tasks->pluck('task_id')->implode(', '),
            'Assigned Tasks' => $this->tasks->pluck('task')->implode(', '),
            'Number of involved tasks' => $this->tasks->count(),
            'Files' => implode(', ', $files),
            'Number of files' =>  count($files),
        ];
    }
}
