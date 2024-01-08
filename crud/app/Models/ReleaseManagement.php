<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ReleaseManagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'project_id',
        'name',
        'details',
        'release_date',
        'approved_by',
        'rmid',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate 8-character UUID-like identifier before creating a new ReleaseManagement instance
        static::creating(function ($releaseManagement) {
            $releaseManagement->uuid = substr(str_replace('-', '', Str::uuid()), 0, 8);
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function documents()
    {
        return $this->hasMany(ReleaseManagementDocument::class);
    }

    public function approver()
    {
        return $this->belongsTo(ProjectMember::class, 'approved_by' , 'project_members_id');
    }

    public function getApprovedByNameAttribute()
    {
        return $this->approver->user->name;
    }

    public function projectMembers()
    {
        return $this->project->projectMembers;
    }

    public function stakeholdersWithMembers()
    {
        return $this->hasMany(Stakeholder::class)->with('projectMember.user');
    }

    public function stakeholders()
    {
        return $this->hasMany(Stakeholder::class);
    }
}
