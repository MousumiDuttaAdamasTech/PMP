<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TaskAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 
        'file_path',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function getFilePathAttribute()
    {
        return Storage::url($this->attributes['file_path']);
    }
}