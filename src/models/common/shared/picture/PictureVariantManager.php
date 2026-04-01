<?php

require_once __DIR__ . '/../../AbstractEntityManager.php';
require_once __DIR__ . '/PictureVariant.php';

class PictureVariantManager extends AbstractEntityManager
{
    public function __construct()
    {
        parent::__construct();
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

        foreach ($definitions as $definition) {
            $generated = $this->generateVariantFile(
                $pictureId,
                $originalFullPath,
                $definition
            );

            if ($generated['success'] === false) {
                continue;
            }

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

        return $savedVariants;
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
                ['width' => 48, 'device' => 'mobile', 'format' => 'webp'],
                ['width' => 100, 'device' => 'desktop', 'format' => 'webp'],
            ],
            'book_card' => [
                ['width' => 160, 'device' => 'mobile', 'format' => 'webp'],
                ['width' => 240, 'device' => 'desktop', 'format' => 'webp'],
            ],
            'book_detail' => [
                ['width' => 320, 'device' => 'mobile', 'format' => 'webp'],
                ['width' => 720, 'device' => 'desktop', 'format' => 'webp'],
            ],
            'cover' => [
                ['width' => 375, 'device' => 'mobile', 'format' => 'webp'],
                ['width' => 1042, 'device' => 'desktop', 'format' => 'webp'],
            ],
            default => [
                ['width' => 320, 'device' => 'all', 'format' => 'webp'],
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

        $sourceImage = $this->createImageResource($originalFullPath, $mimeType);

        if ($sourceImage === null) {
            return [
                'success' => false,
                'error' => 'Unsupported source image resource.'
            ];
        }

        $targetWidth = (int) $definition['width'];
        $targetHeight = (int) round(($sourceHeight / $sourceWidth) * $targetWidth);

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

        $filename = 'picture_' . $pictureId . '_' . $definition['device'] . '_' . $targetWidth . '.webp';
        $fullPath = $absoluteDirectory . $filename;
        $relativePath = $relativeDirectory . $filename;

        imagewebp($targetImage, $fullPath, 85);

        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        return [
            'success' => true,
            'relative_path' => $relativePath,
            'full_path' => $fullPath,
            'width' => $targetWidth,
            'height' => $targetHeight
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