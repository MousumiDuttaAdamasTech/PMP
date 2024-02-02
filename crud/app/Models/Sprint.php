<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    protected $fillable = [
        'sprint_name',
        'estimated_hrs',
        'actual_hrs',
        'sprint_status',
        'current_date',
        'assign_to',
        'projects_id',
        'is_active',
    ];

    public function projectMember()
    {
        return $this->belongsTo(ProjectMember::class, 'assign_to','project_members_id');
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
