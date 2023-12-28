<?php

// app/Models/Document.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_uuid',
        'doc_type_id',
        'doc_name',
        'version',
        'comments',
        'approved_by',
        'approved_on',
        'project_id', // Add this line
        'attachments',
    ];

    protected $dates = ['approved_on'];

    public function doctype()
    {
        return $this->belongsTo(Doctype::class, 'doc_type_id');
    }

    public function approver()
    {
        return $this->belongsTo(ProjectMember::class, 'approved_by');
    }

    // Define an accessor to retrieve the user_name from the associated user
    public function getApprovedByNameAttribute()
    {
        return $this->approver->user->name;
    }

    public function wasUpdated()
    {
        // Compare the created_at and updated_at timestamps
        return $this->created_at != $this->updated_at;
    }
}
