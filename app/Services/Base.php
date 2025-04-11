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

        return $path = $file->storeAs('public', $fileName);
    }
}
