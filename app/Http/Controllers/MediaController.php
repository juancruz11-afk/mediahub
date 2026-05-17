<?php
// app/Http/Controllers/MediaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MediaExtractorService;
use App\Jobs\ProcessMediaDownload;
use App\Models\DownloadHistory;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function __construct(private MediaExtractorService $extractor) {}

    public function fetchInfo(Request $request)
    {
        $request->validate([
            'url' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(tiktok\.com|instagram\.com)\/.+$/i']
        ]);

        try {
            $metadata = $this->extractor->getMetadata($request->url);
            
            return response()->json([
                'title' => $metadata['title'] ?? 'Media',
                'thumbnail' => $metadata['thumbnail'] ?? null,
                'duration' => $metadata['duration'] ?? 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo obtener la información.'], 500);
        }
    }

    public function startDownload(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'format' => 'required|in:mp4,mp3'
        ]);

        $jobId = Str::uuid();
        
        // Registrar en BD con estado "pending"
        $history = DownloadHistory::create([
            'job_id' => $jobId,
            'url' => $request->url,
            'format' => $request->format,
            'status' => 'pending'
        ]);

        // Enviar a la cola de Redis
        ProcessMediaDownload::dispatch($history)->onQueue('downloads');

        return response()->json(['job_id' => $jobId]);
    }

    public function checkStatus($jobId)
    {
        $job = DownloadHistory::where('job_id', $jobId)->firstOrFail();
        return response()->json([
            'status' => $job->status,
            'download_url' => $job->status === 'completed' ? asset('storage/downloads/' . $job->file_name) : null
        ]);
    }
}