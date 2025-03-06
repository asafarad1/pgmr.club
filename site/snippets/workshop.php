<?php foreach ($workshops as $workshop): ?>
    <div class="workshop">
        <div class="workshop-top">
            <div class="workshop-title">
                <h2><?= $workshop->title() ?></h2>
            </div>
            <div class="workshop-description">
                <h2><?= $workshop->about() ?></h2>
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
        <button class="apply-button">
            <h2>הרשמה</h2>
        </button>
    </div>
<?php endforeach ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all apply buttons
        const applyButtons = document.querySelectorAll('.apply-button');

        // Add click event listener to each button
        applyButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the parent workshop element
                const workshopElement = this.closest('.workshop');

                // Toggle the 'clicked' class on the workshop element
                workshopElement.classList.toggle('clicked');
            });
        });
    });
</script>