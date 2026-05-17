<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MediaExtractorService
{
    /**
     * Crea un entorno temporal personalizado en el disco D (dentro del storage de Laravel)
     */
    private function getCustomEnv(): array
    {
        $tempDir = storage_path('app/temp');
        
        // Si no existe la carpeta, la creamos
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Usamos getenv() para obtener el entorno REAL de Windows (incluyendo SystemRoot)
        $env = getenv(); 
        
        // Sobreescribimos solo las variables temporales para usar el disco D:
        $env['TEMP'] = $tempDir;
        $env['TMP'] = $tempDir;
        $env['TMPDIR'] = $tempDir;

        return $env;
    }

    public function getMetadata(string $url): array
    {
        // Le pasamos null como directorio de trabajo, y nuestro getCustomEnv() como entorno
        $process = new Process(['yt-dlp.exe', '--dump-json', '--no-playlist', $url], null, $this->getCustomEnv());
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }

        return json_decode($process->getOutput(), true);
    }

    public function downloadAndConvert(string $url, string $format, string $outputPath): void
    {
        $args = ['yt-dlp.exe', '-o', $outputPath];

        if ($format === 'mp3') {
            $args = array_merge($args, [
                '-x', 
                '--audio-format', 'mp3',
                '--audio-quality', '0'
            ]);
        } else {
            $args = array_merge($args, [
                '-f', 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best'
            ]);
        }

        $args[] = $url;
        
        // También aplicamos el entorno personalizado a la descarga
        $process = new Process($args, null, $this->getCustomEnv());
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }
    }
}