<?php snippet( "header-data" ); ?>

<?php if ( param( "feedback" ) === "true" ) : ?>

<?php snippet( "workshop-feedback-all", [ "workshops" => $workshops, "questions" => $questions ] ); ?>

<?php else : ?>

<table>
    <thead>
        <tr>
            <?php foreach ( $keys as $key => $label ) : ?>
                <th>
                    <?=$label ?>
                </th>
            <?php endforeach; ?>
            <th>שיבוץ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $students as $student ) : ?>
            <tr>
            <?php foreach ( $keys as $key => $label ) : ?>
                <td><?=$student->$key() ?></td>
            <?php endforeach; ?>
            <td><?=$assignments[ $student->id_num()->value() ] ?? "" ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    
<?php endif; ?>

<?php snippet( "footer-data" ); ?>
