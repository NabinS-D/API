<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Models\Project;
use App\Models\File;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['task' ,'description', 'priority'];
    protected $table= 'tasks';
    protected $primaryKey ='task_id';

    public function notes()
    {
        return $this->hasMany(Note::class,'task_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class,'project_tasks', 'task_id', 'project_id');
    }

    public function files()
    {
        return $this->hasMany(File::class,'task_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'task_users','task_id','id');
    }
}
