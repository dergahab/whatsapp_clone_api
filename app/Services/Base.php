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

    public function getFileInfo($file)
    {
        $path=$this->fileUploadStorage($file,'uploads');
        $originalName = str_replace(' ', '', $file->getClientOriginalName());
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'])) {
            $type = 'image';
        } elseif ($extension === 'pdf') {
            $type = 'pdf';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $type = 'word';
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            $type = 'excel';
        } else {
            $type = 'other';
        }
        $sizeInKB = round($file->getSize() / 1024, 2);

        return [
            'attachment_type' => $type,
            'name' => $originalName,
            'size' => $sizeInKB,
            'path'=>$path
        ];
    }

    public function fileDeleteStorage($filePath)
    {
        $relativePath = Str::replaceFirst('storage/', '', $filePath);
        $fullPath = storage_path('app/public/' . $relativePath);
        if (file_exists($fullPath) && is_file($fullPath)) {
            unlink($fullPath);
            return true;
        }
        return false;
    }


}
