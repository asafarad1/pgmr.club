<?php snippet('header') ?>

<div class="intro">
    <h1><?= $page->title() ?></h1>
</div>

<div class="login">
    <div class="text">
    <div class="workshops-header">
            <div class="workshops-header-label">
                <img src="<?= asset("assets/images/logo_full.svg")->url() ?>" alt="pgmr.club-logo">
            </div>
        </div>
        
        <?php if (!empty($alerts)): ?>
            <?php foreach ($alerts as $alert) : ?>
                <div class="alert"><?= $alert ?></div>
            <?php endforeach; ?>
        <?php endif ?>


        <form method="post" action="<?= $page->url() ?>">
            <input type="hidden" name="csrf" value="<?= csrf() ?>">
            <?php if ($status->status() === 'inactive'): ?>
                <div>
                    <label for="tel">מספר טלפון נייד:</label>
                    <input id="tel" name="tel" required type="tel" value="<?= esc($tel ?? "", 'attr') ?>">
                </div>
            <?php endif ?>

            <?php if ($status->status() === 'pending'): ?>
                <div>
                    <label for="name">קוד כניסה</label>
                    <input id="code" name="code" placeholder="000 000" required type="text">
                    <p><small>אנא בדקו את תיבת המייל שלכם וכתבו את הקוד שקיבלתם, אם לא התקבל מייל בדקו בתיקיית הספאם.</small></p>
                </div>
            <?php endif ?>
            <div>
                <input type="submit" name="login" value="כניסה">
            </div>

        </form>
    </div>
    </article>

    <?php snippet('footer') ?>