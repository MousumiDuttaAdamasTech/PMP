<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public const PRIORITIES = [
        'p0 (critical)', 'p1 (high)', 'p2 (medium)', 'p3 (low)',
    ];

    public const TASK_TYPES = [
        'Adhoc', 'New Req',
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
        'actual_hours',
        'task_type',
        'epic', 
        'story',
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

    public static function getTaskTypeOptions()
    {
        return array_combine(self::TASK_TYPES, self::TASK_TYPES);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class, 'task_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }
}
