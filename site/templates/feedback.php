<?php snippet( "header" ); ?>

<div class="intro">
    <h1><?= $page->title() ?></h1>
</div>

<div class="logout">
    <a href="<?=$kirby->url() ?>/logout" class="logout-link">יציאה</a>
</div>

<?php if ( $site->feedback_deadline()->toDate() < time() ) : ?>

<div class="notice">
    <p>הזמן לתת משוב עבר. להתראות בשנה הבאה!</p>
</div>    

<?php else : ?>

<div class="notice">
    <?=$page->intro() ?>
</div>

<form method="post" action="<?=$page->url() ?>" class="feedback-form">

    <?php if ( $success ) : ?>

    <div class="success">
        <p>התשובות שלך נשלחו בהצלחה, תודה!</p>
    </div>

    <?php else : ?>

    <?php if ( !empty( $alerts ) ) : ?>
    <div class="alerts">
        <?php foreach ( $alerts as $alert ) : ?>
            <p><?=$alert ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="form-row">
        <label for="feedback_workshop">הסדנה שהשתתפת בה*</label>
        <div class="select-container">
        <select id="feedback_workshop" name="workshop" required>
            <?php foreach ( $site->getWorkshops() as $workshop ) : ?>
            <option value="<?=$workshop->id() ?>" <?php e( $kirby->user()->role()->id() === "student" && $kirby->user()->getAssignment()?->id() === $workshop->id(), "selected" ); ?>><?=$workshop->title() ?></option>
            <?php endforeach; ?>
        </select>
        </div>
    </div>

    <?php foreach ( $page->questions()->toStructure() as $question ) : ?>

    <div class="form-row">
        <?php if ( in_array( $question->type()->value(), [ "text", "textarea", "dropdown" ] ) ) : ?>
            <label for="feedback_question_<?=$question->name()->value() ?>"><?=$question->question() ?><?php e( $question->required()->toBool(), "<span class='required'>*</span>" ) ?></label>
        <?php else : ?>
            <p class="question-label"><?=$question->question() ?><?php e( $question->required()->toBool(), "<span class='required'>*</span>" ) ?></p>
        <?php endif; ?>
        <?php switch ( $question->type()->value() ) :
            case "text": ?>
            <input type="text" name="question[<?=$question->name()->value() ?>]" id="feedback_question_<?=$question->name() ?>" <?php e( $question->required()->toBool(), "required" ) ?> <?php if ( isset( $data[ $question->name()->value() ] ) ) : ?>value="<?=$data[ $question->name()->value() ] ?>"<?php endif; ?>>
            <?php 
            break;
            case "textarea": ?>
            <textarea name="question[<?=$question->name()->value() ?>]" id="feedback_question_<?=$question->name() ?>" <?php e( $question->required()->toBool(), "required" ) ?>><?php if ( isset( $data[ $question->name()->value() ] ) ) : ?>value="<?=$data[ $question->name()->value() ] ?>"<?php endif; ?></textarea>
            <?php
            break;
            case "radios":
            case "checkboxes": ?>
            <div class="radio-block">
            <?php foreach ( $question->options()->split() as $idx => $option ) : ?>
            <span class="radio-block-choice">
            <input type="<?php e( $question->type()->value() === "radios", "radio", "checkbox" ) ?>" name="question[<?=$question->name() ?>]" id="feedback_question_<?=$question->name() ?>_<?=$idx ?>" value="<?=$option ?>" <?php if ( isset( $data[ $question->name()->value() ] ) && $data[ $question->name()->value() ] == $option ) : ?>checked<?php endif; ?>>
            <label for="feedback_question_<?=$question->name() ?>_<?=$idx ?>"><?=$option ?></label>
            </span>
            <?php
            endforeach; ?>
            </div> 
            <?php
            break;
            case "dropdown": ?>
            <div class="select-container">
            <select name="question[<?=$question->name() ?>]" id="feedback_question_<?=$question->name() ?>" <?php e( $question->required()->toBool(), "required" ) ?>>
            <?php foreach ( $question->options()->split() as $option ) : ?>
            <option value="<?=$option ?>" <?php if ( isset( $data[ $question->name()->value() ] ) && $data[ $question->name()->value() ] == $option ) : ?>selected<?php endif; ?>><?=$option ?></option>
            <?php endforeach; ?>
            </select>
            </div>
            <?php
            break;
        endswitch; ?>  
    </div>

    <?php endforeach; ?>

    <div class="form-row">
        <input type="submit" name="feedback" value="שליחה">
    </div>

    <?php endif; ?>

</form>

<?php endif; ?>


<?php snippet( "footer" ); ?>