<table class="feedback-table">
    <thead>
        <tr>
    <?php foreach ( $questions as $question ) : ?>
        <th><?=$question ?></th>
    <?php endforeach; ?>
        </tr>
        </thead>
    <tbody>
        <?php foreach ( $items as $item ) : ?>
        <tr>
            <?php foreach ( $questions as $question ) : ?>
            <td><?=$item->content()->get( str_replace( "-", "_", $question ) )->value() ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
