<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allnotes = [];  
        foreach($this->notes as $note)
        {  
            $allnotes[] = ([
                'Note ID' => $note->id,
                'Note'  => $note->description,
                'Created at' => $note->created_at,
            ]);
           
        }

        return [
            'Task ID' => $this->task_id,
            'All related Notes' => $allnotes, 
        ];
    }
}
