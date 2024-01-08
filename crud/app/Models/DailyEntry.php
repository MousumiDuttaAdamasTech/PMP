<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_taken',
        'completed_task',
        'description',
        'completed',
    ];
}
