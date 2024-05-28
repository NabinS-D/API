<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['file_name', 'file_loc', 'task_id'];
    protected $table = 'files';
    protected $primaryKey = 'file_id';

    public function tasks()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
