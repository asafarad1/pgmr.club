<?php

return function( $site, $kirby ) {

    if ( !$kirby->user() ) {
        go( "login" );
    }

    date_default_timezone_set( option( "tz" ) );

    $alerts = [];

    $success = [];

    $choices = array_values( $kirby->user()->content()->choices()->toPages()?->toArray( fn($w) => $w->id() ) );

    $workshops = $site->getWorkshops()->sortBy( "title", "asc" );

    if ( get( "choose" ) && $kirby->request()->is( "POST" ) ) {

        if ( $site->deadline()->toDate() > time() ) {

            if ( $choices = get( "choice" ) ) {

                if ( !in_array( "", $choices ) && count( $choices ) === 3 ) {

                    if ( count( array_unique( $choices ) ) === count( $choices ) ) {
                            
                        $user = $kirby->user();
                        $kirby->impersonate( "kirby" );
                        try {
                            $updated_user = $user->update( [ "choices" => $choices ] );
                            go( "choose/success:success" );
                        } catch ( Exception $e ) {
                            $alerts[] = "שגיאה התרחשה במהלך ההרשמה: {$e->getMessage()}";
                        }

                    } else {

                        $alerts[] = "נא לבחור 3 סדנאות שונות.";

                    }

                } else {

                    $alerts[] = "יש לבחור 3 סדנאות.";

                }                

            } else {

                $alerts[] = "לא נבחרו סדנאות";

            }

        } else {

            $alerts[] = "ההרשמה נסגרה.";

        }

    }

    return compact( "workshops", "choices", "alerts", "success" );

};