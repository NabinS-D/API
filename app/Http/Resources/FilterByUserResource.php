<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterByUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     *return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
            return [
                'User ID' => $this->id,
                'User Name' => $this->name,
                'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            ];
               
        
    }
}
