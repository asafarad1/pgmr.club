<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Export assignments to Excel file',
	'args' => [] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {

		if ( $export_file = site()->exportWorkshops() ) {
			janitor()->data( $cli->arg( "command" ), [
				"status" => 200,
				"message" => "Success!",
				"open" => $export_file,
			] );
			return;
		}

			
		janitor()->data( $cli->arg( "command" ), [
			"status" => 400,
			"message" => "An error occured."
		] );
		return;

	}
];
