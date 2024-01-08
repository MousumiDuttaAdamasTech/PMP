<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    use HasFactory;

    protected $fillable = [
        'release_management_id',
        'member_id',
    ];

    public function release_management()
    {
        return $this->belongsTo(ReleaseManagement::class);
    }

    public function projectMember()
    {
        return $this->belongsTo(ProjectMember::class, 'member_id' , 'project_members_id');
    }
}
