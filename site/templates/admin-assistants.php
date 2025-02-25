<?php

if ( $kirby->user()?->role()->name() === "admin" ) : 

$workshops = $site->getWorkshops();

?>

<?php snippet( "header-data" ); ?>

<table>
    <thead>
        <th>שם הסדנה</th>
        <th>מנחה</th>
        <th>איש.אשת קשר</th>
        <th>שנת לימודים</th>
        <th>טלפון</th>
        <th>מייל</th>
    </thead>
    <tbody>
    <?php foreach ( $site->getWorkshops()->sortBy( "title", "asc" ) as $workshop ) : 
        $assistant = $workshop->assistant()->toUser();
        ?>
    <tr>
        <td><?=$workshop->title() ?></td>
        <td><?=$workshop->teacher() ?></td>
        <td><?=$assistant->name() ?></td>
        <td><?=$assistant->year() ?></td>
        <td><?=$assistant->tel() ?></td>
        <td><?=$assistant->email() ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php snippet( "footer-data" ); ?>

<?php endif;