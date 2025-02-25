<?php

return function( $page, $site, $kirby ) {

    if ( $kirby->user()->role()->name() !== "admin" ) {
        go( "/" );
        exit;
    }

    $keys = option( "workshop.keys" );

    $questions = page( "feedback" )?->questions()->toStructure()->toArray( fn($q) => $q->name()->value() );

    $questions = array_merge( $questions, [ "year", "gender" ] );

    return compact( "keys", "questions" );
};