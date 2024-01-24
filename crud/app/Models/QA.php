<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QA extends Model
{
    use HasFactory;

    protected $table = 'qa';

    protected $fillable = [
        'round',
        'module',
        'description',
        'qa_status_id',
        'sprint_id',
    ];

    public function qaStatus()
    {
        return $this->belongsTo(QAStatus::class);
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }
}
