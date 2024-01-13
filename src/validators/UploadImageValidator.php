<?php

class UploadImageValidator
{
    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';


    public function __construct()
    {

    }

    static function validateFile(array $file): ?string
    {
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return 'File is too large for destination file system.';
        }

        if (!isset($file['type']) || !in_array($file['type'], self::SUPPORTED_TYPES)) {
            return 'File type is not supported.';
        }

        return null;
    }
}