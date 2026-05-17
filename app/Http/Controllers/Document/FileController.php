<?php

namespace App\Http\Controllers\Document;

use Illuminate\Http\Request;
use App\Services\Document\FileConverterService;
use App\Jobs\Document\ProcessFile;
use App\Models\Document\FileConversion;
use Illuminate\Support\Str;

class FileController
{
    public function uploadAndConvert(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480', // Máximo 20MB
            'type' => 'required|in:pdf_to_img,img_to_pdf,remove_bg'
        ]);

        $file = $request->file('file');
        // Validar extensión real por seguridad
        if ($request->type === 'pdf_to_img' && $file->extension() !== 'pdf') {
            return response()->json(['error' => 'Debes subir un archivo PDF.'], 422);
        }
        // NUEVO: Validar que sea una imagen para quitar fondo
        if ($request->type === 'remove_bg' && !in_array($file->extension(), ['jpg', 'jpeg', 'png', 'webp'])) {
            return response()->json(['error' => 'Debes subir una imagen válida (JPG, PNG, WEBP).'], 422);
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