<?php

namespace App\Jobs\Document;

use App\Models\Document\FileConversion;
use App\Services\Document\FileConverterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <-- Agregamos esta línea clave

class ProcessFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    public function __construct(public FileConversion $conversion) {}

    public function handle(FileConverterService $converter): void
    {
        $this->conversion->update(['status' => 'processing']);

        try {
            // Laravel calcula la ruta exacta y perfecta para Windows automáticamente
            $storageDir = Storage::disk('public')->path('downloads');
            $inputPath = Storage::disk('local')->path($this->conversion->stored_path);

            // Asegurarnos de que el directorio de descargas exista
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            $resultFile = '';

            // NUEVO: Sacar el nombre original (sin extensión) y limpiarlo
            $baseName = pathinfo($this->conversion->original_name, PATHINFO_FILENAME);
            $safeName = \Illuminate\Support\Str::slug($baseName) . '-' . substr($this->conversion->job_id, 0, 5);

            // Ejecutar la conversión pasándole nuestro nuevo nombre limpio
            if ($this->conversion->conversion_type === 'pdf_to_img') {
                $resultFile = $converter->convertPdfToImages($inputPath, $storageDir, $safeName);
            } 
            elseif ($this->conversion->conversion_type === 'img_to_pdf') {
                $outputPdf = $storageDir . DIRECTORY_SEPARATOR . $safeName . '.pdf';
                $resultFile = $converter->convertImagesToPdf([$inputPath], $outputPdf);
            }
            // === NUEVO MÓDULO ===
            elseif ($this->conversion->conversion_type === 'remove_bg') {
                $outputPng = $storageDir . DIRECTORY_SEPARATOR . $safeName . '.png';
                $resultFile = $converter->removeBackground($inputPath, $outputPng);
            }

            // Actualizar la base de datos con el éxito
            $this->conversion->update([
                'status' => 'completed',
                'result_file' => $resultFile
            ]);

            // Borrar el archivo original subido para ahorrar espacio
            if (file_exists($inputPath)) { 
                unlink($inputPath); 
            }

        } catch (\Exception $e) {
            Log::error("Error Job {$this->conversion->job_id}: " . $e->getMessage());
            $this->conversion->update(['status' => 'failed']);
        }
    }
}