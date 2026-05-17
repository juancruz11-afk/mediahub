<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'url',
        'format',
        'status',
        'file_name',
    ];
}