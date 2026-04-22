<?php
$libraryTableClassName = trim((string) ($libraryTableClassName ?? 'library-table'));
$libraryTableHeaders = is_array($libraryTableHeaders ?? null) ? $libraryTableHeaders : [];
$libraryTableRowsHtml = (string) ($libraryTableRowsHtml ?? '');
$libraryTableCaption = (string) ($libraryTableCaption ?? '');
?>

<div class="library-table-wrapper">
    <table class="<?= htmlspecialchars($libraryTableClassName, ENT_QUOTES, 'UTF-8') ?>">
        <?php if ($libraryTableCaption !== ''): ?>
            <caption class="visually-hidden"><?= htmlspecialchars($libraryTableCaption, ENT_QUOTES, 'UTF-8') ?></caption>
        <?php endif; ?>

        <?php if (!empty($libraryTableHeaders)): ?>
            <thead>
                <tr>
                    <?php foreach ($libraryTableHeaders as $header): ?>
                        <th scope="col"><?= htmlspecialchars((string) $header, ENT_QUOTES, 'UTF-8') ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
        <?php endif; ?>

        <tbody>
            <?= $libraryTableRowsHtml ?>
        </tbody>
    </table>
</div>