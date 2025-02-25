<?php

return function( $page, $site, $kirby ) {

    if ( !$kirby->user() ) {
        go( "login" );
    }

    date_default_timezone_set( option( "tz" ) );

    $alerts = [];

    $success = param( "success" ) === "success";

    $data = [];

    $user = $kirby->user();

    if ( $user->content()->handed_feedback()->toBool() ) {

        $success = true;

    } elseif ( get( "feedback" ) && $kirby->request()->is( "POST" ) ) {

        if ( $site->feedback_deadline()->toDate() > time() ) {

            $answers = get( "question" ) ?? [];
            $data = [ "workshop" => get( "workshop" ) ];
            $rules = [ "workshop" => [ "required" ] ];
            $messages = [ "workshop" => "נא לציין באיזו סדנה השתתפת" ];
            foreach ( $page->questions()->toStructure() as $q ) {
                $data[ $q->name()->value() ] = isset( $answers[ $q->name()->value() ] ) ? $answers[ $q->name()->value() ] : null;
                if ( $q->required()->toBool() ) {
                    $rules[ $q->name()->value() ] = [ "required" ];
                    $messages[ $q->name()->value() ] = "חובה לענות על כל השאלות המסומנות ב-*";
                }
            }
            
            if ( $errors = invalid( $data, $rules, $messages ) ) {
                $alerts = array_merge( $alerts, array_unique( array_values( $errors ) ) );
            } else {

                if ( $workshop = page( $data[ "workshop" ] ) ) {
                    $kirby->impersonate( "kirby" );
                    $content = array_merge( [ 
                        "title" => "Feedback for " . $workshop->title() . " on " . date( "Y-m-d H:i:s" ),
                        "year" => $user->content()->year()->value(),
                        "gender" => $user->content()->gender()->value(),
                    ], $data );
                    try {
                        $workshop->createChild( [
                            "template" => "feedback-item",
                            "draft" => false,
                            "slug" => "feedback-" . $workshop->slug() . "-" . date( "Y-m-d-H-i-s" ),
                            "content" => $content
                        ] );
                        $updated_user = $user->update( [ "handed_feedback" => true ] );
                        go( "feedback/success:success" );
                    } catch ( Exception $e ) {
                        $alerts[] = "שגיאה התרחשה במהלך הוספת המשוב: {$e->getMessage()}";
                    }    
                } else {
                    $alerts[] = "שגיאה: הסדנה לא נמצאה :(";
                }
    
            }

        } else {

            $alerts[] = "הזמן לתת משוב עבר.";

        }

    }

    return compact( "alerts", "success", "data" );

};