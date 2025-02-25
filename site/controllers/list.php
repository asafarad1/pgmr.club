<?php

return function( $site, $kirby ) {

    if ( !$kirby->user() || $kirby->user()->role()->name() === "student" ) {
        go( "login" );
    }

    date_default_timezone_set( option( "tz" ) );

    $workshops = $site->getWorkshops()->sortBy( "title", "asc" );

    return compact( "workshops" );

};