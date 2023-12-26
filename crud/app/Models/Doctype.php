<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctype extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'doc_type',
    ];

}
