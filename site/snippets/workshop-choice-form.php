<p>
<label for="choice_<?=$num ?>"><?=$label ?></label>
<div class="select-container">
<select id="choice_<?=$num ?>" name="choice[<?=$num-1 ?>]">
    <option value="">בחרו סדנה</option>
    <?php foreach ( $workshops as $workshop ) : ?>
        <option value="<?=$workshop->id() ?>" <?php e( $choice === $workshop->id(), "selected" ); ?>>
            <?=$workshop->title()->toString() ?> / <?=strip_tags( $workshop->teacher()->toString() ) ?>
        </option>
    <?php endforeach; ?>
</select>
</div>
</p>
