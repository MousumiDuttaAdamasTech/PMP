<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    public const PRIORITIES = [
        'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8', 'p9', 'p10',
    ];

    protected $fillable = [
        'title',
        'priority',
        'estimated_time',
        'details',
        'project_task_status_id',
        'assigned_to',
        'allotted_to',
        'project_id',
        'parent_task',
        'sprint_id',
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

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task');
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public static function getPriorityOptions()
    {
        return array_combine(self::PRIORITIES, array_map('strtolower', self::PRIORITIES));
    }
}
