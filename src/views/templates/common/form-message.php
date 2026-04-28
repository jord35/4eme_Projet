<?php
$messageId = trim((string) ($messageId ?? ''));
$messageText = (string) ($messageText ?? '');
$messageState = trim((string) ($messageState ?? ''));
$messageHidden = isset($messageHidden) ? (bool) $messageHidden : $messageText === '';
$messageClassName = 'form-message';

if ($messageState !== '') {
    $messageClassName .= ' form-message--' . preg_replace('/[^a-z-]/', '', strtolower($messageState));
}
?>

<div
    <?php if ($messageId !== ''): ?>id="<?= htmlspecialchars($messageId, ENT_QUOTES, 'UTF-8') ?>"<?php endif; ?>
    class="<?= htmlspecialchars($messageClassName, ENT_QUOTES, 'UTF-8') ?>"
    data-state="<?= htmlspecialchars($messageState, ENT_QUOTES, 'UTF-8') ?>"
    role="status"
    aria-live="polite"
    aria-atomic="true"
    <?= $messageHidden ? 'hidden' : '' ?>>
    <?= htmlspecialchars($messageText, ENT_QUOTES, 'UTF-8') ?>
</div>