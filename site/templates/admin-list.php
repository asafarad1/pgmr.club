<?php

if ( $kirby->user()?->role()->name() === "admin" ) : ?>

<?php snippet( "header-data" ); ?>

<table>
    <thead>
        <th>Workshop name</th>
        <th>Workshop teacher</th>
    </thead>
    <tbody>
    <?php foreach ( $site->getWorkshops() as $workshop ) : ?>
    <tr>
        <td><?=$workshop->title() ?></td>
        <td><?=$workshop->teacher() ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php snippet( "footer-data" ); ?>

<?php endif;