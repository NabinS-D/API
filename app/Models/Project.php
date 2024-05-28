<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectNote;
use App\Models\Task;


class Project extends Model
{
    use HasFactory;
    protected $table = "projects";
    protected $primaryKey = 'project_id'; 
    protected $fillable = [
        'project_name' , 'description',
    ];

    public function notes()
    {
        return $this->hasMany(ProjectNote::class, 'project_id');
    }

    public function tasks()
    {
     return $this->belongsToMany(Task::class, 'project_tasks' , 'project_id', 'task_id');
    }
}
