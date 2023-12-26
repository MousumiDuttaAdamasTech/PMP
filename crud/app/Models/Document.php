<?php

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
}
