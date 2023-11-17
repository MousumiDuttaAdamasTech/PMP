<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPrice extends Model
{
    use HasFactory;

    protected $fillable = ['worker_id', 'daily_price', 'monthly_price', 'yearly_price', 'weekly_price'];

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
