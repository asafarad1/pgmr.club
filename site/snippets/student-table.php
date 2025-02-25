<table>
    <thead>
        <tr>
            <th>#</th>
            <?php foreach ( $keys as $key => $label ) : ?>
                <th>
                    <?=$label ?>
                </th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $students as $student ) : ?>
            <tr>
            <td><?=$student->indexOf( $students ) + 1 ?></td>
            <?php foreach ( $keys as $key => $label ) : ?>
                <td><?=$student->$key() ?></td>
            <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
