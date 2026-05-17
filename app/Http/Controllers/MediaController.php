<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MediaExtractorService;
use App\Jobs\ProcessMediaDownload;
use App\Models\DownloadHistory;
use Illuminate\Support\Str;

class MediaController
{
    public function __construct(private MediaExtractorService $extractor) {}

    public function fetchInfo(Request $request)
    {
        // Validar que sea Tiktok o Instagram
        $request->validate([
            'url' => ['required', 'url', 'regex:/^(https?:\/\/)?([a-zA-Z0-9-]+\.)*(tiktok\.com|instagram\.com)\/.+$/i']
        ]);

        try {
            $metadata = $this->extractor->getMetadata($request->url);
            
            return response()->json([
                'title' => $metadata['title'] ?? 'Multimedia Desconocido',
                'thumbnail' => $metadata['thumbnail'] ?? null,
                'duration' => isset($metadata['duration']) ? gmdate("i:s", $metadata['duration']) : '00:00',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function startDownload(Request $request)
    {
        $request->validate([
            'url' => ['required', 'url', 'regex:/^(https?:\/\/)?([a-zA-Z0-9-]+\.)*(tiktok\.com|instagram\.com)\/.+$/i'],
            'format' => 'required|in:mp4,mp3',
            'title' => 'nullable|string|max:200'
        ]);

        $jobId = (string) Str::uuid();

        // NUEVO: Limpiamos el título (quita acentos, emojis y pone guiones)
        $safeTitle = Str::slug($request->input('title', 'video-descargado'));
        // Le pegamos 5 letritas del ID al final por si el usuario descarga 2 videos con el mismo nombre, no se sobreescriban.
        $fileName = $safeTitle . '-' . substr($jobId, 0, 5) . '.' . $request->format;
        
        $history = DownloadHistory::create([
            'job_id' => $jobId,
            'url' => $request->url,
            'format' => $request->format,
            'status' => 'pending',
            'file_name' => $fileName
        ]);

        // Despachar el Job
        ProcessMediaDownload::dispatch($history);

        return response()->json(['job_id' => $jobId]);
    }

    public function checkStatus($jobId)
    {
        $job = DownloadHistory::where('job_id', $jobId)->firstOrFail();
        
        $downloadUrl = null;
        if ($job->status === 'completed' && $job->file_name) {
            $downloadUrl = asset('storage/downloads/' . $job->file_name);
        }

        return response()->json([
            'status' => $job->status,
            'download_url' => $downloadUrl
        ]);
    }
}