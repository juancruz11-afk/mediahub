<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class FileConverterService
{
    // Extrae imágenes de un PDF y las comprime en un ZIP
    public function convertPdfToImages(string $pdfPath, string $outputDir, string $jobId): string
    {
        // 1. Verificar si el archivo existe ANTES de modificar la ruta
        if (!file_exists($pdfPath)) {
            throw new \Exception("El archivo físico no se encuentra en: " . $pdfPath);
        }

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
        
        // 2. realpath() le pide a Windows que nos dé la ruta perfecta y sin mezclas
        $realPdfPath = realpath($pdfPath);
        $realOutputDir = realpath($outputDir);

        // 3. Ahora sí, forzamos las diagonales normales para que Poppler no llore
        $popplerPdfPath = str_replace('\\', '/', $realPdfPath);
        $popplerOutputDir = str_replace('\\', '/', $realOutputDir);
        $prefix = $popplerOutputDir . '/' . 'page';

        // Tu ruta (está perfecta)
        $popplerExe = 'D:/HerramientasCLI/poppler/poppler-24.08.0/Library/bin/pdftoppm.exe'; 
        
        $process = new Process([$popplerExe, '-jpeg', $popplerPdfPath, $prefix]);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception("Error en pdftoppm: " . $process->getErrorOutput());
        }

        // 4. Crear el ZIP
        $zipPath = $realOutputDir . DIRECTORY_SEPARATOR . $jobId . '.zip';
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            // Buscar los JPGs generados
            $files = glob($realOutputDir . DIRECTORY_SEPARATOR . 'page-*.jpg');
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
            
            // Limpiar las imágenes sueltas
            foreach ($files as $file) { unlink($file); }
        }

        return $jobId . '.zip';
    }

    // Une varias imágenes en un solo PDF
    public function convertImagesToPdf(array $imagePaths, string $outputPdfPath): string
    {
        // En Windows el comando es 'magick.exe'
        $args = array_merge(['magick.exe'], $imagePaths, [$outputPdfPath]);
        
        $process = new Process($args);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception("Error en magick: " . $process->getErrorOutput());
        }

        return basename($outputPdfPath);
    }
}