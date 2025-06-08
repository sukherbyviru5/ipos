<?php

namespace App\Models;

use Google\Service\Storage;
use Illuminate\Database\Eloquent\Model;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriveGoogle extends Model
{
    use HasFactory;

    static function fileViews($filename, $folder)
    {
        $files = Gdrive::all($folder, true);
        foreach ($files as $file) {
            if (isset($file['extra_metadata']['id']) && isset($file['extra_metadata']['name'])) {
                if ($file['extra_metadata']['name'] === $filename) {
                    return "https://drive.google.com/file/d/{$file['extra_metadata']['id']}/preview";
                }
            }
        }
        return null;
    }
}
