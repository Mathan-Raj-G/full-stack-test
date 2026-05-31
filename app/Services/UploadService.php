<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class UploadService
{
    public function store(array $file, string $type, ?string $existingPath = null): string
    {
        $rules = config('allowed_uploads.' . $type);

        if (!is_array($rules)) {
            throw new RuntimeException('Invalid upload configuration.');
        }

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException($this->errorMessage((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE)));
        }

        if (($file['size'] ?? 0) > $rules['max_size']) {
            throw new RuntimeException('The uploaded file is too large.');
        }

        $tmpName = $file['tmp_name'] ?? '';
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = $finfo ? finfo_file($finfo, $tmpName) : false;

        if ($finfo) {
            finfo_close($finfo);
        }

        if ($mimeType === false || !in_array($mimeType, $rules['mime_types'], true)) {
            throw new RuntimeException('Unsupported file type uploaded.');
        }

        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));

        if (!in_array($extension, $rules['extensions'], true)) {
            throw new RuntimeException('Invalid file extension uploaded.');
        }

        $directory = (string) config('uploads.' . $type);
        $absoluteDirectory = base_path($directory);

        if (!is_dir($absoluteDirectory) && !mkdir($absoluteDirectory, 0775, true) && !is_dir($absoluteDirectory)) {
            throw new RuntimeException('Unable to prepare the upload directory.');
        }

        $filename = sprintf('%s-%s.%s', $type, bin2hex(random_bytes(10)), $extension);
        $relativePath = trim($directory, '/') . '/' . $filename;
        $absolutePath = base_path($relativePath);

        if (!move_uploaded_file($tmpName, $absolutePath)) {
            throw new RuntimeException('The file could not be moved to the upload directory.');
        }

        if ($existingPath) {
            $this->deleteManagedFile($existingPath);
        }

        return str_replace('\\', '/', $relativePath);
    }

    public function deleteManagedFile(?string $relativePath): void
    {
        if (!is_upload_path($relativePath)) {
            return;
        }

        $absolutePath = absolute_public_path($relativePath);

        if ($absolutePath && is_file($absolutePath)) {
            unlink($absolutePath);
        }
    }

    private function errorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the configured size limit.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'Please select a file to upload.',
            default => 'The upload failed. Please try again.',
        };
    }
}
