<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory;

    protected $table = 'project_members';

    protected $fillable = [
        'project_id',
        'project_members_id',
        'project_role_id',
        'engagement_percentage',
        'start_date',
        'end_date',
        'duration',
        'is_active',
        'engagement_mode',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Define the relationship with the User model (assuming 'project_members_id' is the foreign key)
    public function user()
    {
        return $this->belongsTo(User::class, 'project_members_id');
    }

    // Define the relationship with the ProjectRole model
    public function projectRole()
    {
        return $this->belongsTo(ProjectRole::class, 'project_role_id');
    }
}
