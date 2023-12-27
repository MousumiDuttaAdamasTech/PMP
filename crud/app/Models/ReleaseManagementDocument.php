<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseManagementDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'release_management_id',
        'document_path',
    ];

    public function releaseManagement()
    {
        return $this->belongsTo(ReleaseManagement::class);
    }

    public function getDocumentUrlAttribute()
    {
        return Storage::url($this->document_path);
    }
}
