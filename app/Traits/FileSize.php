<?php

namespace App\Traits;


trait FileSize
{

    public function fileSize($fileSizeInBytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($fileSizeInBytes, 0);
        if (!in_array('', $units)) {
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
        } else {
            $pow = array_search('', $units);
        }

        // Calculate byte value by the appropriate unit
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
