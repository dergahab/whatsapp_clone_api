<?php

namespace App\Services;

use Illuminate\Support\Str;

class Base
{
    public function fileUploadStorage($file, $directory)
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = $file->getClientOriginalName();
        $slugifiedName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        $timestamp = now()->format('Y-m-d_His');
        $directory = 'uploads/files/'.$directory;
        $fileName = "{$directory}/{$slugifiedName}_{$timestamp}.{$extension}";
        $file->storeAs('public', $fileName);

        return 'storage/'.$fileName;
    }

    public function fileDeleteStorage($filePath)
    {
        $relativePath = Str::replaceFirst('storage/', '', $filePath);
        $fullPath = storage_path('app/public/'.$relativePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);

            return true;
        }

        return false;
    }
}
