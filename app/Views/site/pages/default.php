<?php
/** @var array $page */
?>
<div class="page-head">
    <div class="wrap">
        <h1><?= e((string) $page['title']) ?></h1>
        <?php if (!empty($page['intro'])): ?>
            <p class="lede"><?= e((string) $page['intro']) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="wrap wrap--text section">
    <div class="prose"><?= safe_html((string) $page['body']) ?></div>
</div>
