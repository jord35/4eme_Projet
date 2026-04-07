<?php

class PictureManager extends AbstractEntityManager
{
    private PictureVariantManager $pictureVariantManager;
    private PictureStorage $pictureStorage;

    public function __construct()
    {
        parent::__construct();
        $this->pictureVariantManager = new PictureVariantManager();
        $this->pictureStorage = new PictureStorage();
    }

    /**
     * Récupère un package image complet pour un contexte donné.
     *
     * @param int $pictureId
     * @param string $context
     * @return array
     */
    public function getPicturePackage(int $pictureId, string $context): array
    {
        $picture = $this->findById($pictureId);

        if (!$picture) {
            return [];
        }

        $variants = $this->pictureVariantManager->findByPictureIdAndContext($pictureId, $context);

        $src = $picture->getOriginalPath();
        $srcsetParts = [];
        $width = $picture->getWidth();
        $height = $picture->getHeight();

        foreach ($variants as $variant) {
            $srcsetParts[] = $variant->getPath() . ' ' . $variant->getWidth() . 'w';

            if ($variant->getDevice() === 'desktop') {
                $src = $variant->getPath();
                $width = $variant->getWidth();
                $height = $variant->getHeight();
            }
        }

        return [
            'id' => $picture->getId(),
            'title' => $picture->getTitle(),
            'alt' => $picture->getAltText(),
            'description' => $picture->getTitle(),
            'src' => $src,
            'srcset' => implode(', ', $srcsetParts),
            'sizes' => $this->buildSizesByContext($context),
            'width' => $width,
            'height' => $height,
            'original' => [
                'path' => $picture->getOriginalPath(),
                'filename' => $picture->getOriginalFilename(),
                'mime_type' => $picture->getMimeType()
            ]
        ];
    }

    /**
     * Sauvegarde une image source puis demande la création des variantes.
     *
     * @param array $file
     * @param array $options
     * @return array
     */
    public function savePicture(array $file, array $options = []): array
    {
        $validation = $this->validateUploadedFile($file);

        if ($validation['success'] === false) {
            return $validation;
        }

        $originalData = $this->storeOriginalFile($file);

        if ($originalData['success'] === false) {
            return $originalData;
        }

        $imageInfo = getimagesize($originalData['full_path']);

        $picture = new Picture([
            'title' => $options['title'] ?? null,
            'alt_text' => $options['alt_text'] ?? null,
            'original_path' => $originalData['relative_path'],
            'original_filename' => $file['name'] ?? null,
            'mime_type' => mime_content_type($originalData['full_path']),
            'width' => $imageInfo[0] ?? null,
            'height' => $imageInfo[1] ?? null
        ]);

        $pictureId = $this->insert($picture);

        if ($pictureId <= 0) {
            return [
                'success' => false,
                'error' => 'Failed to insert picture in database.',
                'data' => null
            ];
        }

        $variantPreset = $options['variant_type'] ?? 'generic';

        $variantsResult = $this->pictureVariantManager->generateAndSaveVariants(
            $pictureId,
            $originalData['full_path'],
            $variantPreset
        );

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'picture_id' => $pictureId,
                'original_path' => $originalData['relative_path'],
                'variants' => $variantsResult
            ]
        ];
    }

    /**
     * Récupère une image par son id.
     *
     * @param int $pictureId
     * @return Picture|null
     */
    public function findById(int $pictureId): ?Picture
    {
        $sql = 'SELECT * FROM pictures WHERE id = :id LIMIT 1';
        $result = $this->db->query($sql, ['id' => $pictureId])->fetch();

        if (!$result) {
            return null;
        }

        return new Picture($result);
    }
    public function deletePicturePackageIfUnused(int $pictureId): void
    {
        if ($pictureId <= 0) {
            return;
        }

        if ($this->isStillReferenced($pictureId)) {
            return;
        }

        $picture = $this->findById($pictureId);

        if (!$picture instanceof Picture) {
            return;
        }

        $this->pictureVariantManager->deleteByPictureId($pictureId);
        $this->pictureStorage->deleteRelativePath($picture->getOriginalPath());

        $sql = 'DELETE FROM pictures WHERE id = :id';
        $this->db->query($sql, [
            'id' => $pictureId
        ]);
    }

    
    /**
     * Insère une image dans la table pictures.
     *
     * @param Picture $picture
     * @return int
     */
    private function insert(Picture $picture): int
    {
        $sql = '
            INSERT INTO pictures (
                title,
                alt_text,
                original_path,
                original_filename,
                mime_type,
                width,
                height
            ) VALUES (
                :title,
                :alt_text,
                :original_path,
                :original_filename,
                :mime_type,
                :width,
                :height
            )
        ';

        $params = [
            'title' => $picture->getTitle(),
            'alt_text' => $picture->getAltText(),
            'original_path' => $picture->getOriginalPath(),
            'original_filename' => $picture->getOriginalFilename(),
            'mime_type' => $picture->getMimeType(),
            'width' => $picture->getWidth(),
            'height' => $picture->getHeight()
        ];

        $this->db->query($sql, $params);

        return (int) $this->db->getPDO()->lastInsertId();
    }

    /**
     * Vérifie le fichier uploadé.
     *
     * @param array $file
     * @return array
     */
    private function validateUploadedFile(array $file): array
    {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return [
                'success' => false,
                'error' => 'Invalid uploaded file.',
                'data' => null
            ];
        }

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'error' => 'Upload error.',
                'data' => null
            ];
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $mimeType = mime_content_type($file['tmp_name']);

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return [
                'success' => false,
                'error' => 'Unsupported image format.',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => null
        ];
    }

    /**
     * Sauvegarde le fichier original sur le disque.
     *
     * @param array $file
     * @return array
     */
    private function storeOriginalFile(array $file): array
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $uniqueName = uniqid('picture_', true) . '.' . $extension;

        $relativeDirectory = '/uploads/pictures/original/';
        $absoluteDirectory = ROOT_DIR . 'public' . $relativeDirectory;

        if (!is_dir($absoluteDirectory)) {
            mkdir($absoluteDirectory, 0777, true);
        }

        $fullPath = $absoluteDirectory . $uniqueName;
        $relativePath = $relativeDirectory . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            return [
                'success' => false,
                'error' => 'Failed to move uploaded file.',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'relative_path' => $relativePath,
            'full_path' => $fullPath
        ];
    }

    /**
     * Définit la règle sizes selon le contexte.
     *
     * @param string $context
     * @return string
     */
    private function buildSizesByContext(string $context): string
    {
        return match ($context) {
            'profile' => '(max-width: 768px) 48px, 100px',
            'book_card' => '(max-width: 768px) 160px, 240px',
            'book_detail' => '(max-width: 768px) 320px, 720px',
            'cover' => '(max-width: 768px) 375px, 1042px',
            default => '100vw',
        };
    }
    private function isStillReferenced(int $pictureId): bool
    {
        $bookCount = (int) $this->db->query(
            'SELECT COUNT(*) AS total FROM books WHERE cover_picture_id = :id',
            ['id' => $pictureId]
        )->fetch()['total'];

        $userCount = (int) $this->db->query(
            'SELECT COUNT(*) AS total FROM users WHERE profile_picture_id = :id',
            ['id' => $pictureId]
        )->fetch()['total'];

        return $bookCount > 0 || $userCount > 0;
    }
}