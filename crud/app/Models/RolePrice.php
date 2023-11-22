<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePrice extends Model
{
    use HasFactory;
    
    protected $fillable = ['role_id', 'daily_price', 'monthly_price', 'yearly_price', 'weekly_price'];

    public function role()
    {
        return $this->belongsTo(ProjectRole::class, 'role_id');
    }
}
