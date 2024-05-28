<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectNoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
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
            'Project ID' => $this->project_id,
            'All related Notes' => $allnotes, 
        ];
    }
}
