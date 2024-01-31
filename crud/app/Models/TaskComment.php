<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'task_comments';

    protected $fillable = ['task_id', 'member_id', 'comment', 'parent_comment'];

    /**
     * Get the task associated with the comment.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function projectMember()
    {
        return $this->belongsTo(ProjectMember::class, 'member_id' , 'project_members_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    /**
     * Get the parent comment (if any).
     */
    public function parentComment()
    {
        return $this->belongsTo(TaskComment::class, 'parent_comment');
    }

    /**
     * Get the replies to the comment.
     */
    public function replies()
    {
        return $this->hasMany(TaskComment::class, 'parent_comment');
    }

    /**
     * Scope to get only the root-level comments (not replies).
     */
    public function scopeRootComments($query)
    {
        return $query->whereNull('parent_comment');
    }

    /**
     * Scope to get only soft-deleted comments.
     */
    public function scopeOnlyTrashed($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope to get both active and soft-deleted comments.
     */
    public function scopeWithTrashed($query)
    {
        return $query->withTrashed();
    }

    /**
     * Scope to get only edited comments.
     */
    public function scopeEdited($query)
    {
        return $query->whereNotNull('deleted_at');
    }

}
