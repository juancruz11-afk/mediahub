<?php
namespace App\Models\Document;
use Illuminate\Database\Eloquent\Model;

class FileConversion extends Model
{
    protected $fillable = ['job_id', 'original_name', 'stored_path', 'conversion_type', 'status', 'result_file'];
}