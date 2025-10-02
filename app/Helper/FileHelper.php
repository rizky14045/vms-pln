<?php 

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class FileHelper {
    
    public static function generatedFileName($prefix, $extension) {
        return $prefix.'-'. time() . '.' . $extension;
    }
    
    public static function saveFile($file, $path, $filename) {
        $file->move(public_path($path), $filename);
        return $path . '/' . $filename;
    } 

    public static function base64Encode($filePath) {
        if (file_exists($filePath)) {
            $fileData = file_get_contents($filePath);
            return base64_encode($fileData);
        }
        return null;
    }

    public static function toBase64($file, bool $withPrefix = true): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $fileContent = file_get_contents($file->getRealPath());
        $base64 = base64_encode($fileContent);

        if ($withPrefix) {
            $mimeType = $file->getMimeType();
            return "data:" . $mimeType . ";base64," . $base64;
        }

        return $base64;
    }
}