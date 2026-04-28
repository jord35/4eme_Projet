<?php

class PictureStorage
{
    public function deleteRelativePath(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $fullPath = ROOT_DIR . 'public' . $relativePath;

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}