<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'doc_name',
        'doc_type_id',
        'comments',
        'approved_by',
        'approved_on',
        'project_id',
        'version',
        'attachments',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
