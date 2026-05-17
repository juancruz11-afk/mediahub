<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileConversion;
use App\Jobs\ProcessFile;
use Illuminate\Support\Str;

class FileController
{
    public function uploadAndConvert(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480', // Máximo 20MB
            'type' => 'required|in:pdf_to_img,img_to_pdf'
        ]);

        $file = $request->file('file');
        // Validar extensión real por seguridad
        if ($request->type === 'pdf_to_img' && $file->extension() !== 'pdf') {
            return response()->json(['error' => 'Debes subir un archivo PDF.'], 422);
        }

        $jobId = (string) Str::uuid();
        
        // Guardamos el archivo subido en storage/app/uploads
        $path = $file->store('uploads');

        $conversion = FileConversion::create([
            'job_id' => $jobId,
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $path,
            'conversion_type' => $request->type,
            'status' => 'pending'
        ]);

        ProcessFile::dispatch($conversion);

        return response()->json(['job_id' => $jobId]);
    }

    public function checkStatus($jobId)
    {
        $job = FileConversion::where('job_id', $jobId)->firstOrFail();
        
        return response()->json([
            'status' => $job->status,
            'download_url' => $job->status === 'completed' ? asset('storage/downloads/' . $job->result_file) : null
        ]);
    }
}