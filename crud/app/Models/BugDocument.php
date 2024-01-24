<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BugDocument extends Model
{
    use HasFactory;

    public function bugid()
    {
        return $this->belongsTo(Bugs::class, "bug_id", "id");
    }
}
