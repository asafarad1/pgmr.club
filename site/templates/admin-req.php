<?php

if ( $kirby->user()?->role()->name() === "admin" ) : ?>

<?php snippet( "header-data" ); ?>

<table>
    <thead>
        <th>שם הסדנה</th>
        <th>מנחה</th>
        <th>ציוד/הכנה נדרשת</th>
    </thead>
    <tbody>
    <?php foreach ( $site->getWorkshops() as $workshop ) : ?>
    <tr>
        <td><?=$workshop->title() ?></td>
        <td><?=$workshop->teacher() ?></td>
        <td><?=$workshop->requirements() ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php snippet( "footer-data" ); ?>

<?php endif;