<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class ProjectNote extends Model
{
    use HasFactory;
    protected $table = 'project_notes';
    protected $fillable = [
        'project_id', 'description'
    ];

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
