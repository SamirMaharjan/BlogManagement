<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Fileable
{
    /**
     * Upload file.
     *
     * @param UploadedFile $file The file to be uploaded.
     * @param string $dir The directory where the file should be stored.
     * @param string $disk The disk to use for storage.
     * @param string|null $filename The desired filename (optional).
     * @param bool $saveOriginal Whether to save the original file.
     * @throws \Exception If an error occurs during the upload process.
     */
    public function uploadFile(UploadedFile $file, string $dir, string $disk = 's3', ?string $filename = null, bool $saveOriginal = false): array
    {
        try {
            $newFileName = $filename ?: time() . '_' . $file->getClientOriginalName();
            $filePath = $saveOriginal ? "{$dir}/{$newFileName}" : null;
            $filePathUrl = $this->store($file, $filePath, $disk);
            return [
                'original' => $filePathUrl,
                'filename' => $newFileName,
                'directory' => $dir,
            ];
        } catch (\Exception $e) {
            logger()->error('Error storing file: ' . $e->getMessage());
            echo $e->getMessage();
            return [
                'error' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Store the original file and return its URL.
     *
     * @param UploadedFile $file The file to be stored.
     * @param string|null $path The storage path for the file.
     * @param string $disk The disk to use for storage.
     * @return string|null The URL of the stored file, or null if not stored.
     */
    private function store(UploadedFile $file, ?string $path, string $disk = 's3'): ?string
    {
        try {
            if ($path) {
                Storage::disk($disk)->put($path, file_get_contents($file));
                $url = Storage::disk($disk)->url($path);
                return $url;
            }
        } catch (\Exception $e) {
            logger()->error('Error storing file: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Remove the original file.
     *
     * @param string $fileName The name of the file to be removed.
     * @param string $dir The directory where the file is stored.
     * @param string $disk The disk to use for storage.
     * @return void
     */
    public function removeFile(string $fileName, string $dir, string $disk = 's3'): void
    {
        $filePath = "{$dir}/original/{$fileName}";
        $this->removeFileMethod($disk, $filePath);
    }

    /**
     * Remove the file based on the specified disk and file path.
     *
     * @param string $disk The disk to use for storage.
     * @param string $filePath The file path in the storage.
     * @return void
     */
    public function removeFileMethod(string $disk, string $filePath): void
    {
        if (Storage::disk($disk)->exists($filePath)) {
            Storage::disk($disk)->delete($filePath);
        }
    }

    /**
     * Upload image content.
     *
     * @param string $content The image content to be uploaded.
     * @param string $dir The directory where the image should be stored.
     * @param string $disk The disk to use for storage.
     * @param string|null $filename The desired filename (optional).
     * @param bool $saveOriginal Whether to save the original image.
     * @throws \Exception If an error occurs during the upload process.
     */
    public function uploadImageToBucket(string $content, string $dir, string $disk = 's3', ?string $filename = null, bool $saveOriginal = false): array
    {
        $newFileName = $filename ?: time() . '_image.jpg';
        $filePath = $saveOriginal ? "{$dir}/{$newFileName}" : null;
        $filePathUrl = $this->storeContent($content, $filePath, $disk);
        return [
            'original' => $filePathUrl,
            'filename' => $newFileName,
            'directory' => $dir,
        ];
    }

    /**
     * Store content and return its URL.
     *
     * @param string $content The content to be stored.
     * @param string|null $path The storage path for the content.
     * @param string $disk The disk to use for storage.
     * @return string|null The URL of the stored content, or null if not stored.
     */
    private function storeContent(string $content, ?string $path, string $disk = 's3'): ?string
    {
        if ($path) {
            Storage::disk($disk)->put($path, $content);
            return Storage::disk($disk)->url($path);
        }

        return null;
    }

    /**
     * storeFileFromPath the original file and return its URL.
     *
     * @param string $disk The disk to use for storage.
     * @return string|null The URL of the stored file, or null if not stored.
     */
    private function storeFileFromPath(string $path, string $filePath , string $disk = 's3'): ?string
    {
        try {
            if ($path) {
                Storage::disk($disk)->put($path, file_get_contents($filePath));
                $url = Storage::disk($disk)->url($path);
                return $url;
            }
        } catch (\Exception $e) {
            logger()->error('Error storing file: ' . $e->getMessage());
        }

        return null;
    }
}
