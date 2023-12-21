<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $fillable = ['task_id', 'assigned_to','allotted_to'];

    // Define the relationship between TaskUser and Task
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function assignedToUser()
    {
        return $this->belongsTo(ProjectMember::class, 'assigned_to', 'id');
    }

    public function allottedToUser()
    {
        return $this->belongsTo(ProjectMember::class, 'allotted_to', 'id');
    }
}

