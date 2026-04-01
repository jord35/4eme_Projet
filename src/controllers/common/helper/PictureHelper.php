<?php

class PictureHelper 
{
    private PictureManager $pictureManager;

    public function __construct()
    {
        $this->pictureManager = new PictureManager();
    }

    public function getPicturePackage(int $pictureId, string $context): array
    {
        if ($pictureId <= 0) {
            return [
                'success' => false,
                'error' => 'Invalid picture id.',
                'data' => null
            ];
        }

        if (trim($context) === '') {
            return [
                'success' => false,
                'error' => 'Invalid picture context.',
                'data' => null
            ];
        }

        $result = $this->pictureManager->getPicturePackage($pictureId, $context);

        if (empty($result)) {
            return [
                'success' => false,
                'error' => 'Picture package not found.',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => $result
        ];
    }

    public function savePicture(array $file, array $options = []): array
    {
        if (empty($file) || empty($file['tmp_name'])) {
            return [
                'success' => false,
                'error' => 'No uploaded file provided.',
                'data' => null
            ];
        }

        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'error' => 'Upload failed.',
                'data' => null
            ];
        }

        $result = $this->pictureManager->savePicture($file, $options);

        if (empty($result) || (isset($result['success']) && $result['success'] === false)) {
            return [
                'success' => false,
                'error' => $result['error'] ?? 'Picture save failed.',
                'data' => $result['data'] ?? null
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => $result['data'] ?? $result
        ];
    }
}