<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'priority',
        'estimated_time',
        'details',
        'project_task_status_id',
        'assigned_to',
        'allotted_to',
        'project_id',
    ];

    public function taskUsers()
    {
        return $this->hasMany(TaskUser::class, 'task_id');
    }

    public function projectTaskStatus()
    {
        return $this->belongsTo(ProjectTaskStatus::class);
    }

    public function assignedToUsers()
    {
        $assignedToIds = explode(',', $this->assigned_to);
        return ProjectMember::whereIn('id', $assignedToIds)->get();
    }

    public function allottedToUsers()
    {
        $allottedToIds = explode(',', $this->allotted_to);
        return ProjectMember::whereIn('id', $allottedToIds)->get();
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
