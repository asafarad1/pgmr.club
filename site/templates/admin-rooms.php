<?php

if ( $kirby->user()?->role()->name() === "admin" ) : 

$workshops = $site->getWorkshops();
$days = [];
foreach ( $workshops->toArray( fn( $w ) => $w->rooms()->toStructure()->toArray( fn( $r ) => $r->day()->value() ) ) as $workshop_days ) {
    foreach ( $workshop_days as $day ) {
        if ( !in_array( $day, $days ) ) {
            $days[] = $day;
        }
    }
}

?>

<?php snippet( "header-data" ); ?>

<table>
    <thead>
        <th>שם הסדנה</th>
        <th>מנחה</th>
        <?php foreach ( $days as $day ) : ?>
        <th><?=$day ?></th>
        <?php endforeach; ?>
    </thead>
    <tbody>
    <?php foreach ( $site->getWorkshops()->sortBy( "title", "asc" ) as $workshop ) : 
        $rooms = $workshop->rooms()->toStructure();
        ?>
    <tr>
        <td><?=$workshop->title() ?></td>
        <td><?=$workshop->teacher() ?></td>
        <?php foreach ( $days as $day ) : ?>
        <th><?=$rooms->findBy( "day", $day )?->room() ?></th>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php snippet( "footer-data" ); ?>

<?php endif;