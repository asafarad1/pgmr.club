<?php snippet( "header" ); ?>

<div class="intro">
    <h1><?= $page->title() ?></h1>
</div>

<div class="logout">
    <a href="<?=$kirby->url() ?>/logout" class="logout-link">יציאה</a>
</div>

<?php if ( $site->deadline()->toDate() < time() ) : ?>

<div class="notice">
    <p>הזמן עבר וההרשמה נעולה. להתראות בשבוע הסדנאות!</p>
</div>    

<?php else : ?>

<div class="notice">
    <?=$page->intro() ?>
    <p>ההרשמה תיסגר בעוד <span class="countdown" data-end="<?=$site->deadline()->toDate() ?>"></span>.</p>
</div>

<div class="workshops-layout">

<form method="post" action="<?=$page->url() ?>">

    <?php if ( param( "success" ) === "success" ) : ?>

    <div class="success">
        <p>הבחירות שלך נשמרו בהצלחה, תודה.</p>
        <ul>
        <?php foreach ( $choices as $choice ) : $c = page( $choice ); ?>
            <li><?=$c->title() ?> / <?=strip_tags($c->teacher()) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    
    <?php else : ?>

    <h2>בחרו סדנאות ב-3 סדרי עדיפות:</h2>

    <?php if ( !empty( $alerts ) ) : ?>
    <div class="alerts">
        <?php foreach ( $alerts as $alert ) : ?>
            <p><?=$alert ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php snippet( "workshop-choice-form", [ "num" => 1, "label" => "עדיפות ראשונה", "choice" => $choices && !empty( $choices[ 0 ] ) ? $choices[ 0 ] : "" ] ); ?>
    <?php snippet( "workshop-choice-form", [ "num" => 2, "label" => "עדיפות שנייה", "choice" => $choices && !empty( $choices[ 1 ] ) ? $choices[ 1 ] : "" ] ); ?>
    <?php snippet( "workshop-choice-form", [ "num" => 3, "label" => "עדיפות שלישית", "choice" => $choices && !empty( $choices[ 2 ] ) ? $choices[ 2 ] : "" ] ); ?>

    <p>
        <input type="submit" name="choose" value="שליחה">
    </p>

    <?php endif; ?>

    <div class="form-comment">
    <?=$page->comment() ?>
    </div>

    <h2>שאלות ותשובות:</h2>
    <div class="faq">
        <?php foreach ( $page->faq()->toStructure() as $faq_row ) : ?>
        <details>
            <summary class="faq-question"><?=$faq_row->question() ?></summary>
            <div class="faq-answer">
                <?=$faq_row->answer() ?>
            </div>
        </details>
        <?php endforeach; ?>
    </div>

</form>

<div class="workshops-list">

<h2>רשימת הסדנאות</h2>

<?php foreach ( $workshops as $workshop ) : ?>

    <div class="workshop">
        <h3 class="workshop-title"><?=$workshop->title() ?></h2>
        <div class="workshop-teacher"><?=$workshop->teacher() ?>
        <?php if ( $workshop->duration()->isNotEmpty() ) : ?>
            <p><?=$workshop->duration() ?></p>
        <?php endif; ?>
        </div>
        <div class="workshop-about">
            <?=$workshop->about() ?>
        </div>
        <div class="workshop-bio">
            <?=$workshop->bio() ?>
        </div>
    </div>

<?php endforeach; ?>

</div>

</div>

<a href="#" class="link-to-top">בחזרה למעלה</a>

<?php endif; ?>


<?php snippet( "footer" ); ?>