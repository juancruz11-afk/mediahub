<?php

namespace App\Jobs;

use App\Models\DownloadHistory;
use App\Services\MediaExtractorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessMediaDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hora

    public function __construct(public DownloadHistory $download) {}

    public function handle(MediaExtractorService $extractor): void
    {
        $this->download->update(['status' => 'processing']);

        try {
            // Asegurar que el directorio exista
            $storageDir = storage_path('app/public/downloads');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            $fileName = $this->download->file_name ?? ($this->download->job_id . '.' . $this->download->format);
            $outputPath = $storageDir . '/' . $fileName;

            // Ejecutar descarga
            $extractor->downloadAndConvert($this->download->url, $this->download->format, $outputPath);

            // Actualizar BD
            $this->download->update([
                'status' => 'completed',
                'file_name' => $fileName
            ]);

        } catch (\Exception $e) {
            Log::error("Error descargando Job {$this->download->job_id}: " . $e->getMessage());
            $this->download->update(['status' => 'failed']);
        }
    }
}