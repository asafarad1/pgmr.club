<?php if ($workshops->isNotEmpty()): ?>
    <?php foreach ($workshops as $workshop): ?>
        <?php if ($workshop->project_status() == "normal") : ?>
            <?php if (!$workshop->participants()->toUsers()->has($kirby->user())) : ?>
                <div class="workshop">
                <?php else : ?>
                    <div class="workshop registered">
                    <?php endif; ?>
                    <div class="workshop-top">
                        <div class="workshop-title">
                            <h2><?= $workshop->title() ?></h2>
                        </div>
                        <div class="workshop-description">
                            <?= $workshop->about() ?>
                        </div>
                        <?php foreach ($workshop->teacher()->split() as $teacher): ?>
                            <div class="workshop-teacher"><?= $teacher ?></div>
                        <?php endforeach ?>
                        <div class="workshop-rooms">
                            <p><?= $workshop->rooms() ?></p>
                        </div>
                        <div class="workshop-time">
                            <span><?= $workshop->start_time()->toDate('H:i') ?></span>
                            <span>—</span>
                            <span><?= $workshop->end_time()->toDate('H:i') ?></span>
                        </div>
                        <div class="workshop-tags">
                            <?php foreach ($workshop->workshop_type()->split() as $type): ?>
                                <div class="workshop-type"><?= $type ?></div>
                            <?php endforeach ?>
                            <?php foreach ($workshop->target_audience()->split() as $audience): ?>
                                <div class="workshop-Target-audience"><?= $audience ?></div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <?php if (!$workshop->participants()->toUsers()->has($kirby->user()) && ($kirby->user())) : ?>
                        <?php if ($workshop->getAvailability()) : ?>
                            <form method="post">
                                <input type="hidden" name="action" value="register">
                                <input type="hidden" name="workshop_id" value="<?= $workshop->id() ?>">
                                <button type="submit" class="apply-button register">
                                    <h2>הרשמה</h2>
                                </button>
                            </form>
                        <?php else : ?>
                            No room left.
                        <?php endif; ?>
                    <?php elseif ($kirby->user()) : ?>
                        <form method="post">
                            <input type="hidden" name="action" value="unregister">
                            <input type="hidden" name="workshop_id" value="<?= $workshop->id() ?>">
                            <button type="submit" class="apply-button unregister">
                                <h2>נרשמתי &nbsp;<?= $workshop->emoji() ?></h2>
                            </button>
                        </form>
                    <?php else : ?>
                        <button type="submit" class="apply-button nouser">
                                <h2></h2>
                        </button>
                    <?php endif; ?>
                    </div>
                <?php elseif ($workshop->project_status() == "vacation") : ?>
                    <div class="workshop vacation">
                        <h2><?= $workshop->title() ?></h2>
                    </div>
                <?php elseif ($workshop->project_status() == "presentation") : ?>
                    <div class="workshop presentation">
                        <h2><?= $workshop->title() ?></h2>
                    </div>
                <?php endif; ?>
            <?php endforeach ?>
        <?php else : ?>
            <div class="workshop blank">
                <div class="workshop-top">
                    <div class="workshop-title">
                        <h2>יפורסם בקרוב</h2>
                    </div>
                    <div class="blank-rect"></div>
                    <div class="blank-rect"></div>
                    <div class="blank-sep"></div>
                    <div class="blank-rect"></div>
                    <div class="blank-sep"></div>
                    <div class="blank-tags">
                        <div class="blank-tag-type"></div>
                        <div class="blank-tag-Target-audience"></div>
                    </div>
                    <div class="blank-sep"></div>
                </div>
                <div class="apply-button"></div>
            </div>
        <?php endif; ?>