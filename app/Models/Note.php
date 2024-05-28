<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'description'] ;
    protected $table = 'notes';

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_id');
    }
}
