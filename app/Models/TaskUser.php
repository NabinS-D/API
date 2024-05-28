<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    use HasFactory;
    protected $fillable = ['task_id','id'];
    protected $table = 'task_users';
    protected $primaryKey = 'task_user_id';
}
