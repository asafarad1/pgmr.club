<?php snippet( "header-data" ); ?>

<h1><?=$page->title() ?> / <?=strip_tags( $page->teacher()->toString() ) ?></h1>

<?php if ( param( "feedback" ) === "true" ) : ?>

<?php snippet( "workshop-feedback", [ "questions" => $questions, "items" => $page->getFeedbackItems() ] ); ?>

<?php else : ?>

<?php snippet( "student-table", [ "keys" => $keys, "students" => $page->participants()->toUsers()->sortBy( "lname", "asc" ) ] ); ?>

<?php endif; ?>

<?php snippet( "footer-data" ); ?>
