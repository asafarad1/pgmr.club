<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Reset participants in workshop',
	'args' => [] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {
        $workshop = page( $cli->arg( 'page' ) );
        if ( $workshop?->intendedTemplate()->name() !== "workshop" ) {
            janitor()->data( $cli->arg( "command" ), [
                "status" => 400,
                "message" => "No workshop."
            ] );
            return;
        }
        try {
            $workshop = $workshop->update( [ "participants" => [] ] );
            janitor()->data( $cli->arg( "command" ), [
                "status" => 200,
                "message" => "Success!",
                "reload" => true,
            ] );
        } catch ( Exception $e ) {
            janitor()->data( $cli->arg( "command" ), [
                "status" => 500,
                "message" => $e->getMessage()
            ] );
        }
	}
];
