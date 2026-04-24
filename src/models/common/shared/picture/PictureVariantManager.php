<?php

require_once __DIR__ . '/../../AbstractEntityManager.php';
require_once __DIR__ . '/PictureVariant.php';
require_once __DIR__ . '/PictureStorage.php';

class PictureVariantManager extends AbstractEntityManager
{
    private PictureStorage $pictureStorage;

    public function __construct()
    {
        parent::__construct();
        $this->pictureStorage = new PictureStorage();
    }

    /**
     * Récupère les variantes d'une image pour un contexte donné.
     *
     * @param int $pictureId
     * @param string $context
     * @return array
     */
    public function findByPictureIdAndContext(int $pictureId, string $context): array
    {
        $sql = '
            SELECT *
            FROM picture_variant
            WHERE picture_id = :picture_id
              AND variant_type = :variant_type
            ORDER BY width ASC
        ';

        $rows = $this->db->query($sql, [
            'picture_id' => $pictureId,
            'variant_type' => $context
        ])->fetchAll();

        $variants = [];

        foreach ($rows as $row) {
            $variants[] = new PictureVariant($row);
        }

        return $variants;
    }

    /**
     * Récupère toutes les variantes liées à une image.
     *
     * @param int $pictureId
     * @return array
     */
    public function findByPictureId(int $pictureId): array
    {
        $sql = '
            SELECT *
            FROM picture_variant
            WHERE picture_id = :picture_id
            ORDER BY width ASC
        ';

        $rows = $this->db->query($sql, [
            'picture_id' => $pictureId


        ])->fetchAll();

        $variants = [];

        foreach ($rows as $row) {
            $variants[] = new PictureVariant($row);
        }

        return $variants;
    }

    /**
     * Supprime toutes les variantes d'une image, en base et sur disque.
     *
     * @param int $pictureId
     * @return void
     */
    public function deleteByPictureId(int $pictureId): void
    {
        if ($pictureId <= 0) {
            return;
        }

        $variants = $this->findByPictureId($pictureId);

        foreach ($variants as $variant) {
            $this->pictureStorage->deleteRelativePath($variant->getPath());
        }

        $sql = '
            DELETE FROM picture_variant
            WHERE picture_id = :picture_id
        ';

        $this->db->query($sql, [
            'picture_id' => $pictureId
        ]);
    }

    /**
     * Génère les variantes à partir du fichier original et les enregistre.
     *
     * @param int $pictureId
     * @param string $originalFullPath
     * @param string $variantType
     * @return array
     */
    public function generateAndSaveVariants(int $pictureId, string $originalFullPath, string $variantType): array
    {
        $definitions = $this->getVariantDefinitions($variantType);
        $savedVariants = [];
        $errors = [];
        $generatedDimensionKeys = [];

        foreach ($definitions as $definition) {
            $generated = $this->generateVariantFile(
                $pictureId,
                $originalFullPath,
                $definition
            );

            if ($generated['success'] === false) {
                $errors[] = [
                    'device' => $definition['device'],
                    'width' => (int) ($definition['target_width'] ?? $definition['target_size'] ?? 0),
                    'format' => (string) $definition['format'],
                    'error' => $generated['error'] ?? 'Variant generation failed.'
                ];
                continue;
            }

            $dimensionKey = $generated['width'] . 'x' . $generated['height'];

            if (isset($generatedDimensionKeys[$dimensionKey])) {
                continue;
            }

            $generatedDimensionKeys[$dimensionKey] = true;

            $variant = new PictureVariant([
                'picture_id' => $pictureId,
                'format' => $definition['format'],
                'width' => $generated['width'],
                'height' => $generated['height'],
                'device' => $definition['device'],
                'variant_type' => $variantType,
                'path' => $generated['relative_path']
            ]);

            $variantId = $this->insert($variant);
            $variant->setId($variantId);

            $savedVariants[] = $variant;
        }

        $expectedCount = count($definitions);
        $generatedCount = count($savedVariants);

        return [
            'success' => $generatedCount === $expectedCount,
            'variants_complete' => $generatedCount === $expectedCount,
            'expected_count' => $expectedCount,
            'generated_count' => $generatedCount,
            'errors' => $errors,
            'variants' => $savedVariants
        ];
    }

    /**
     * Insère une variante dans la base.
     *
     * @param PictureVariant $variant
     * @return int
     */
    private function insert(PictureVariant $variant): int
    {
        $sql = '
            INSERT INTO picture_variant (
                picture_id,
                format,
                width,
                height,
                device,
                variant_type,
                path
            ) VALUES (
                :picture_id,
                :format,
                :width,
                :height,
                :device,
                :variant_type,
                :path
            )
        ';

        $params = [
            'picture_id' => $variant->getPictureId(),
            'format' => $variant->getFormat(),
            'width' => $variant->getWidth(),
            'height' => $variant->getHeight(),
            'device' => $variant->getDevice(),
            'variant_type' => $variant->getVariantType(),
            'path' => $variant->getPath()
        ];

        $this->db->query($sql, $params);

        return (int) $this->db->getPDO()->lastInsertId();
    }

    /**
     * Retourne la liste des variantes à produire selon le type demandé.
     *
     * @param string $variantType
     * @return array
     */
    private function getVariantDefinitions(string $variantType): array
    {
        return match ($variantType) {
            'profile' => [
                ['target_width' => 48, 'target_height' => 48, 'resize_mode' => 'cover_box', 'device' => 'mobile', 'format' => 'webp'],
                ['target_width' => 135, 'target_height' => 135, 'resize_mode' => 'cover_box', 'device' => 'desktop', 'format' => 'webp'],
            ],
            'book_card' => [
                ['target_width' => 160, 'target_height' => 160, 'resize_mode' => 'cover_box', 'device' => 'mobile', 'format' => 'webp'],
                ['target_width' => 200, 'target_height' => 200, 'resize_mode' => 'cover_box', 'device' => 'desktop', 'format' => 'webp'],
            ],
            'book_table' => [
                ['target_width' => 79, 'target_height' => 79, 'resize_mode' => 'cover_box', 'device' => 'mobile', 'format' => 'webp'],
                ['target_width' => 78, 'target_height' => 78, 'resize_mode' => 'cover_box', 'device' => 'desktop', 'format' => 'webp'],
            ],
            'book_detail' => [
                ['target_width' => 375, 'target_height' => 450, 'resize_mode' => 'cover_box', 'device' => 'mobile', 'format' => 'webp'],
                ['target_width' => 720, 'target_height' => 863, 'resize_mode' => 'cover_box', 'device' => 'desktop', 'format' => 'webp'],
            ],
            'cover' => [
                ['target_width' => 335, 'target_height' => 335, 'resize_mode' => 'cover_box', 'device' => 'mobile', 'format' => 'webp'],
                ['target_width' => 488, 'target_height' => 488, 'resize_mode' => 'cover_box', 'device' => 'desktop', 'format' => 'webp'],
            ],
            default => [
                ['target_width' => 320, 'target_height' => 320, 'resize_mode' => 'cover_box', 'device' => 'all', 'format' => 'webp'],
            ],
        };
    }

    /**
     * Génère physiquement une variante sur le disque.
     *
     * @param int $pictureId
     * @param string $originalFullPath
     * @param array $definition
     * @return array
     */
    private function generateVariantFile(int $pictureId, string $originalFullPath, array $definition): array
    {
        $sourceInfo = getimagesize($originalFullPath);

        if ($sourceInfo === false) {
            return [
                'success' => false,
                'error' => 'Invalid source image.'
            ];
        }

        $sourceWidth = $sourceInfo[0];
        $sourceHeight = $sourceInfo[1];
        $mimeType = $sourceInfo['mime'];
        $targetWidth = (int) ($definition['target_width'] ?? $definition['target_size'] ?? 0);
        $targetHeight = (int) ($definition['target_height'] ?? $definition['target_size'] ?? 0);
        $resizeMode = (string) ($definition['resize_mode'] ?? 'width');

        if ($targetWidth <= 0 || $targetHeight <= 0) {
            return [
                'success' => false,
                'error' => 'Invalid target dimensions.'
            ];
        }

        $sourceImage = $this->createImageResource($originalFullPath, $mimeType);

        if ($sourceImage === null) {
            return [
                'success' => false,
                'error' => 'Unsupported source image resource.'
            ];
        }

        $dimensions = $this->resolveTargetDimensions(
            $sourceWidth,
            $sourceHeight,
            $targetWidth,
            $targetHeight,
            $resizeMode
        );

        $targetWidth = $dimensions['width'];
        $targetHeight = $dimensions['height'];

        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        imagealphablending($targetImage, true);
        imagesavealpha($targetImage, true);

        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight
        );

        $relativeDirectory = '/uploads/pictures/variants/';
        $absoluteDirectory = dirname(__DIR__, 5) . '/public' . $relativeDirectory;

        if (!is_dir($absoluteDirectory)) {
            mkdir($absoluteDirectory, 0777, true);
        }

        $filename = 'picture_' . $pictureId . '_' . $definition['device'] . '_' . $targetWidth . 'x' . $targetHeight . '.webp';
        $fullPath = $absoluteDirectory . $filename;
        $relativePath = $relativeDirectory . $filename;

        $saved = imagewebp($targetImage, $fullPath, 85);

        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        if ($saved === false) {
            return [
                'success' => false,
                'error' => 'Failed to save generated variant.'
            ];
        }

        return [
            'success' => true,
            'relative_path' => $relativePath,
            'full_path' => $fullPath,
            'width' => $targetWidth,
            'height' => $targetHeight
        ];
    }

    private function resolveTargetDimensions(int $sourceWidth, int $sourceHeight, int $targetWidth, int $targetHeight, string $resizeMode): array
    {
        if ($resizeMode === 'cover_box') {
            if ($sourceWidth <= $targetWidth || $sourceHeight <= $targetHeight) {
                return [
                    'width' => $sourceWidth,
                    'height' => $sourceHeight,
                ];
            }

            $scale = max($targetWidth / $sourceWidth, $targetHeight / $sourceHeight);

            return [
                'width' => max(1, (int) round($sourceWidth * $scale)),
                'height' => max(1, (int) round($sourceHeight * $scale)),
            ];
        }

        if ($resizeMode === 'min_side') {
            $smallestSide = min($sourceWidth, $sourceHeight);

            if ($smallestSide <= $targetWidth) {
                return [
                    'width' => $sourceWidth,
                    'height' => $sourceHeight,
                ];
            }

            $scale = $targetWidth / $smallestSide;

            return [
                'width' => max(1, (int) round($sourceWidth * $scale)),
                'height' => max(1, (int) round($sourceHeight * $scale)),
            ];
        }

        if ($sourceWidth <= $targetWidth) {
            return [
                'width' => $sourceWidth,
                'height' => $sourceHeight,
            ];
        }

        return [
            'width' => $targetWidth,
            'height' => max(1, (int) round(($sourceHeight / $sourceWidth) * $targetWidth)),
        ];
    }

    /**
     * Crée une ressource image GD à partir du mime type.
     *
     * @param string $path
     * @param string $mimeType
     * @return GdImage|resource|null
     */
    private function createImageResource(string $path, string $mimeType)
    {
        return match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default => null,
        };
    }
}
