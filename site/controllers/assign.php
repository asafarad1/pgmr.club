<?php

return function( $page, $site, $kirby ) {

    if ( $kirby->user()->role()->name() !== "admin" ) {
        go( "/" );
        exit;
    }

    $alerts = [];

    $keys = option( "students.keys" );

    $students = $kirby->users()->filterBy( "role", "student" );

    $order = [];
    foreach ( $page->content()->order()->yaml() as $criterion ) {
        $order = array_merge( $order, array_values( $criterion ) );
    }
    $order = array_merge( $order, [ "name", "asc" ] );
    $students = $students->sortBy( ...$order );

    $workshops = $site->getWorkshops();
    $assignments = [];
    
    foreach ( $workshops as $workshop ) {
        foreach ( $workshop->participants()->yaml() as $student_uuid ) {
            if ( $s = $students->findBy( "uuid", $student_uuid ) ) {
                $assignments[ $s->id_num()->value() ] = $workshop->title()->toString();
            }
        }
    }

    $questions = page( "feedback" )?->questions()->toStructure()->toArray( fn($q) => $q->name()->value() );

    $questions = array_merge( $questions, [ "year", "gender" ] );

    return compact( "order", "keys", "students", "assignments", "workshops", "questions" );
};