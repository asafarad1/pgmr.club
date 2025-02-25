<?php

use Kirby\Exception\PermissionException;
use Kirby\Toolkit\V;

return function( $kirby, $site ) {

    $alerts = [];

    $status = $kirby->auth()->status();

    $tel = "";

    if ( $status->status() === "active" ) {
        if ( $kirby->user()?->role()->name() === "teacher" ) {
            $redirect_url = $site->login_redirect_teacher()->toPage()?->url();
        } else {
            $redirect_url = $site->login_redirect()->toPage()?->url();
        }
        go( $redirect_url );
    }

    if ( get( "login" ) && $kirby->request()->is( "POST" ) ) {

        if ( csrf( get( "csrf" ) ) === true ) {

            if ( $tel = get( "tel" ) ) {

                $tel = preg_replace( "/\D/", "", $tel );
                $tel = preg_replace( "/^972/", "0", $tel );

                if ( V::minLength( $tel, 10 ) && V::maxLength( $tel, 10 ) ) {

                    $tel = preg_replace( "/^(\d{3})(\d{7})$/", "$1-$2", $tel );

                    if ( $user = $kirby->users()->findBy( "tel", $tel ) ) {
                        $kirby->impersonate( "kirby" );
                        try {
                            $status = $kirby->auth()->createChallenge( $user->email(), false, "login" );
                            $challenge_created = true;
                        } catch ( PermissionException $e ) {
                            $alerts[] = $e->getMessage();
                        }
                    } else {
                        $tel = "";
                        $challenge_created = true; // Pretend like email has been sent if no user has been found
                    }

                } else {

                    $alerts[] = "אנא הזינו מספר טלפון תקני";

                }

            } elseif ( $code = get( "code" ) ) {

                try {
                    $user = $kirby->auth()->verifyChallenge( $code );
                    if ( $user ) {
                        if ( $user->role()->name() === "teacher" ) {
                            $redirect_url = $site->login_redirect_teacher()->toPage()?->url();
                        } else {
                            $redirect_url = $site->login_redirect()->toPage()?->url();
                        }
                        if ( ! $redirect_url ) {
                            $redirect_url = "choose";
                        }
                        go( $redirect_url );
                    }
                } catch ( Exception $e ) {
                    if ( $e->getMessage() == "Invalid code" ) {
                        $alerts[] = "קוד לא תקין. אנא נסו שוב";
                    } else {
                        $alerts[] = $e->getMessage();
                    }
                }

            }

        } else {
            $alerts[] = "תקלה: Invalid CSRF Token. אנא רפרשו ונסו שנית. אם זה לא עובד דברו איתנו.";
        }

    }

    return compact( "alerts", "status", "tel" );

};