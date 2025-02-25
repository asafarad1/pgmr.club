<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Reset participants in all workshops',
	'args' => [] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {
        foreach ( $cli->kirby()->site()->getWorkshops() as $workshop ) {
            try {
                $workshop = $workshop->update( [ "participants" => [] ] );
            } catch ( Exception $e ) {
                janitor()->data( $cli->arg( "command" ), [
                    "status" => 500,
                    "message" => $e->getMessage()
                ] );
            }
        }
        janitor()->data( $cli->arg( "command" ), [
            "status" => 200,
            "message" => "Success!",
            "reload" => true,
        ] );
	}
];
