<?php snippet( "header" ); ?>

<div class="gallery">
    <?php foreach ( $site->getWorkshops() as $workshop ) : ?>
        <h2 class="workshop-title"><?=$workshop->title() ?></h2>
        <?php foreach ( $workshop->files() as $item ) : ?>
            <?php if ( $item->type() === "image" ) : ?>
                <figure class="gallery-item gallery-image-item">
                    <img src="<?=$item->thumb( [ "width" => 480 ] )->url() ?>" alt="">
                    <?php if ( $item->content()->caption()->isNotEmpty() ) : ?>
                    <figcaption><?=$item->content()->caption() ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>

<?php snippet( "footer" ); ?>
