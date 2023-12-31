<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project';

    protected $fillable = [
        'project_name',
        'project_type',
        'project_description',
        'project_manager_id',
        'project_startDate',
        'project_endDate',
        'project_status',
        'client_spoc_name',
        'client_spoc_email',
        'client_spoc_contact',
        'vertical_id',
        'technology_id',
        'client_id',
        'task_type_id',
        'task_status_id'
    ];

    // Relationships

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'Project_manager_id');
    }

    public function vertical()
    {
        return $this->belongsTo(Vertical::class, 'vertical_id');
    }

    public function technologies()
    {
        return $this->belongsTo(Technologies::class, 'technology_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function projectMembers()
    {
        return $this->belongsToMany(Profile::class, 'project_members', 'project_id', 'project_members_id')
            ->withPivot('project_role_id','engagement_percentage','start_date','end_date','duration','is_active','engagement_mode')
            ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(ProjectRole::class, 'project_members', 'project_id', 'project_role_id')
                    ->withTimestamps();
    }

    public function role()
    {
        return $this->belongsTo(ProjectRole::class, 'project_role_id');
    }

    public function task_type()
    {
        return $this->belongsTo(Task_type::class, 'task_type_id');
    }

    public function task_status()  
    {
        return $this->belongsTo(Task_status::class, 'task_status_id');
    }

    public function projectTaskTypes()
    {
        return $this->hasMany(ProjectTaskType::class, 'project_id', 'id');
    }

    public function taskStatuses()
    {
        return $this->belongsToMany(TaskStatus::class, 'project_task_status', 'project_id', 'task_status_id')->withTimestamps();
    }

    public function projectTaskStatuses()
    {
        return $this->hasMany(ProjectTaskStatus::class, 'project_id');
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function members()
    {
        return $this->hasMany(ProjectMember::class, 'project_id');
    }

    public function releaseManagements()
    {
        return $this->hasMany(ReleaseManagement::class);
    }
}