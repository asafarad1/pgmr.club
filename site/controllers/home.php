<?php

return function( $site, $kirby ) {

    $workshops = $site->getWorkshops()->sortBy( "title", "asc" );

    return compact( "workshops" );

};