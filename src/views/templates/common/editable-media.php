<?php
$mediaInputId = trim((string) ($mediaInputId ?? 'media_input'));
$mediaInputName = trim((string) ($mediaInputName ?? 'media_input'));
$mediaLabel = (string) ($mediaLabel ?? 'Modifier');
$mediaPreviewSrc = (string) ($mediaPreviewSrc ?? '');
$mediaPreviewAlt = (string) ($mediaPreviewAlt ?? '');
$mediaPreviewWidth = (int) ($mediaPreviewWidth ?? 200);
$mediaPreviewHeight = (int) ($mediaPreviewHeight ?? 200);
$mediaPreviewId = trim((string) ($mediaPreviewId ?? ''));
$mediaAccept = trim((string) ($mediaAccept ?? 'image/*'));
$mediaClassName = trim((string) ($mediaClassName ?? 'editable-media'));
$mediaFallbackSrc = trim((string) ($mediaFallbackSrc ?? ''));
?>

<div class="<?= htmlspecialchars($mediaClassName, ENT_QUOTES, 'UTF-8') ?>">
    <img
        <?php if ($mediaPreviewId !== ''): ?>id="<?= htmlspecialchars($mediaPreviewId, ENT_QUOTES, 'UTF-8') ?>" <?php endif; ?>
        class="<?= htmlspecialchars($mediaClassName, ENT_QUOTES, 'UTF-8') ?>__preview"
        src="<?= htmlspecialchars($mediaPreviewSrc, ENT_QUOTES, 'UTF-8') ?>"
        alt="<?= htmlspecialchars($mediaPreviewAlt, ENT_QUOTES, 'UTF-8') ?>"
        width="<?= $mediaPreviewWidth ?>"
        height="<?= $mediaPreviewHeight ?>"
        <?php if ($mediaFallbackSrc !== ''): ?>onerror="this.onerror=null;this.src='<?= htmlspecialchars($mediaFallbackSrc, ENT_QUOTES, 'UTF-8') ?>';" <?php endif; ?>>

    <div class="<?= htmlspecialchars($mediaClassName, ENT_QUOTES, 'UTF-8') ?>__edit-wrapper">
        <label class="<?= htmlspecialchars($mediaClassName, ENT_QUOTES, 'UTF-8') ?>__edit link-action" for="<?= htmlspecialchars($mediaInputId, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($mediaLabel, ENT_QUOTES, 'UTF-8') ?>
        </label>
        <input
            class="visually-hidden"
            type="file"
            id="<?= htmlspecialchars($mediaInputId, ENT_QUOTES, 'UTF-8') ?>"
            name="<?= htmlspecialchars($mediaInputName, ENT_QUOTES, 'UTF-8') ?>"
            accept="<?= htmlspecialchars($mediaAccept, ENT_QUOTES, 'UTF-8') ?>">
    </div>
</div>