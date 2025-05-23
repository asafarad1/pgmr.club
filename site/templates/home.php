<?php snippet("header"); ?>

<div class="credit-user credit-user-top">
        <?php if ($kirby->user()) : ?>
            <span>שלום,</span>
            <span><?= $kirby->user()->fname() ?> <?= $kirby->user()->lname() ?></span>
        <?php else : ?>
            <span>לא מחובר.ת</span>
            <a href="/login">לכניסה</a>
        <?php endif; ?>
</div>

<div class="workshops-header">
    <div class="workshops-header-label">
        <img src="<?= asset("assets/images/logo_full.svg")->url() ?>" alt="pgmr.club-logo">
    </div>
    <div class="workshops-header-days">
        <div class="workshops-header-day">
            <h2>שני</h2>
        </div>
        <div class="workshops-header-day">
            <h2>רביעי</h2>
        </div>
    </div>
</div>

<div class="workshops-layout">
    <?= $alert ?>
    <?php foreach ($page->weeks()->toPages() as $week): ?>
        <div class="workshops-week">
            <div class="workshops-week-header">
                <h2 class="week-header-date"><?= $week->monday_date()->toDate('d/m') ?></h2>
                <h2>שבוע <?= str_pad($week->week_number(), 2, '0', STR_PAD_LEFT) ?></h2>
                <h2 class="week-header-date"><?= $week->wednesday_date()->toDate('d/m') ?></h2>
                </h2>
            </div>
            <div class="workshops-week-lists">
                <div class="workshops-list-monday">
                    <?php snippet('workshop', ['workshops' => $week->monday_workshops()->toPages()]); ?>
                </div>
                <div class="workshops-list-wednesday">
                    <?php snippet('workshop', ['workshops' => $week->wednesday_workshops()->toPages()]); ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<div class="credit-container">
    <div class="credit-user">
        <?php if ($kirby->user()) : ?>
            <span>שלום,</span>
            <span><?= $kirby->user()->fname() ?> <?= $kirby->user()->lname() ?></span>
        <?php else : ?>
            <span>לא מחובר.ת</span>
            <a href="/login">לכניסה</a>
        <?php endif; ?>
    </div>
    <div class="credits">
        <p>מועדון.פג״מר ⚑ איל, אסף ויובל</p>
        <p>ימי שני ורביעי 17:30 — 20:30</p>
    </div>
    <div class="credits">
        <p>עיצוב אתר: ענבל שמש</p>
        <p>תמיכה טכנית: מאיר סדן</p>
        <p>פונטים באדיבות <a href="https://hagilda.com/" target="_blank">הגילדה</a></p>
    </div>
</div>

<a href="#" class="link-to-top">
    <img src="<?= asset("assets/images/logo_small.svg")->url() ?>" alt="pgmr.club-logo">
</a>

<?php snippet("footer"); ?>