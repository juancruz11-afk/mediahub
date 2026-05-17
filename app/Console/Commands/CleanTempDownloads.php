<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DownloadHistory;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTempDownloads extends Command
{
    protected $signature = 'downloads:clean';
    protected $description = 'Elimina archivos descargados con más de 2 horas de antigüedad';

    public function handle()
    {
        $oldDownloads = DownloadHistory::where('created_at', '<', Carbon::now()->subHours(2))
                                       ->whereNotNull('file_name')
                                       ->get();

        foreach ($oldDownloads as $download) {
            $filePath = 'public/downloads/' . $download->file_name;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            // Opcional: borrar el registro de la BD o solo poner el file_name en null
            $download->update(['file_name' => null]);
        }

        $this->info('Archivos temporales limpiados correctamente.');
    }
}