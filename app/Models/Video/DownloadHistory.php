<?php

namespace App\Models\Video;

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