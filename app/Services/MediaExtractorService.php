<?php
// app/Services/MediaExtractorService.php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MediaExtractorService
{
    /**
     * Obtiene los metadatos sin descargar el archivo aún.
     */
    public function getMetadata(string $url): array
    {
        // Pasamos los argumentos como array para que Symfony los escape automáticamente
        $process = new Process([
            'yt-dlp', 
            '--dump-json', 
            '--no-playlist', 
            $url
        ]);
        
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }

    /**
     * Descarga y convierte el archivo.
     */
    public function downloadAndConvert(string $url, string $format, string $outputPath): void
    {
        $args = ['yt-dlp', '-o', $outputPath];

        if ($format === 'mp3') {
            $args = array_merge($args, [
                '-x', 
                '--audio-format', 'mp3',
                '--audio-quality', '0'
            ]);
        } else {
            // Configuración para MP4 (Tiktok/IG sin marca de agua depende del extractor interno de yt-dlp)
            $args = array_merge($args, [
                '-f', 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best'
            ]);
        }

        $args[] = $url;
        
        $process = new Process($args);
        $process->setTimeout(3600); // Dar tiempo suficiente para la conversión
        $process->run();

        if (!$process->isSuccessful()) {
            \Log::error('Fallo en yt-dlp: ' . $process->getErrorOutput());
            throw new ProcessFailedException($process);
        }
    }
}