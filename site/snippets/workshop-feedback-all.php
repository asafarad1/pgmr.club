<h1>משובים על שבוע הסדנאות 2024: טבלה מרוכזת</h1>

<table class="feedback-table">
<thead>
        <tr>
            <th>Workshop</th>
    <?php foreach ( $questions as $question ) : ?>
        <th><?=$question ?></th>
    <?php endforeach; ?>
        </tr>
</thead>
<tbody>
<?php foreach ( $workshops as $workshop ) : ?>
        <?php foreach ( $workshop->getFeedbackItems() as $item ) : ?>
        <tr>
            <td><?=$workshop->title() ?></td>
            <?php foreach ( $questions as $question ) : ?>
            <td><?=$item->content()->get( str_replace( "-", "_", $question ) )->value() ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
<?php endforeach; ?>
    </tbody>
</table>
