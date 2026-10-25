<?php
use App\Models\SizeGuide;
/** @var array $page */
/** @var array $guides */
?>
<div class="page-head">
    <div class="wrap">
        <h1><?= e((string) $page['title']) ?></h1>
        <?php if (!empty($page['intro'])): ?>
            <p class="lede"><?= e((string) $page['intro']) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="wrap section" style="max-width:96rem;">
    <?php foreach ($guides as $guide): ?>
        <?php if ($guide['rows'] === []) { continue; } ?>
        <?php $columns = SizeGuide::usedColumns($guide['rows']); ?>
        <div class="guide-block">
            <h2><?= e(SizeGuide::title((string) $guide['gkey'], $lang)) ?></h2>
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><?= e(t('product.size')) ?></th>
                            <?php foreach ($columns as $column): ?>
                                <th><?= e(SizeGuide::header($column, $lang)) ?> (cm)</th>
                            <?php endforeach; ?>
                            <th>EU</th><th>IT</th><th>UK</th><th>US</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($guide['rows'] as $row): ?>
                            <tr>
                                <th scope="row"><?= e((string) $row['label']) ?></th>
                                <?php foreach ($columns as $column): ?>
                                    <td><?= e((string) $row[$column]) ?></td>
                                <?php endforeach; ?>
                                <td><?= e((string) $row['eu']) ?></td>
                                <td><?= e((string) $row['it']) ?></td>
                                <td><?= e((string) $row['uk']) ?></td>
                                <td><?= e((string) $row['us']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="prose"><?= safe_html((string) $page['body']) ?></div>
</div>
