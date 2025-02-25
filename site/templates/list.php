<?php snippet( "header" ); ?>

<div class="intro">
    <h1><?= $page->title() ?></h1>
</div>

<div class="logout">
    <a href="<?=$kirby->url() ?>/logout" class="logout-link">יציאה</a>
</div>

<div class="notice">
    <?=$page->intro() ?>
</div>

<div class="workshops-layout">

<div class="workshop-sidebar">

    <p class="lead-image">
        <img src="<?=asset( "assets/images/main.gif" )->url() ?>" alt="">
    </p>

    <p>
        אוצרות שבוע הסדנאות: סוניה אוליטסקי, דן עוזרי ומאיר סדן<br>
        הפקה: שירה חזות<br>
        עיצוב גרפי: הילה קדמון ינאי ושי בלאו<br>
    </p>

    <?php if ( $page->faq()->isNotEmpty() ) : ?>
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
    <?php endif; ?>

</div>

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

<?php snippet( "footer" ); ?>