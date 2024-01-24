<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bugs extends Model
{
    use HasFactory;
    public function qaid()
    {
        return $this->belongsTo(QA::class, "qa_id", "id");
    }
    public function tester_id()
    {
        return $this->belongsTo(ProjectMember::class, 'tester_id', 'project_members_id');
    }
    public function task_id()
    {
        return $this->belongsTo(Task::class);
    }
    public function bugtype()
    {
        return $this->belongsTo(BugType::class, 'bugType', 'id');
    }
}
