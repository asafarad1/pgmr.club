<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Populate random choices for all students',
	'args' => [] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {

		$workshops = $cli->kirby()->site()->getWorkshops();

		$students = $cli->kirby()->site()->getRelevantStudents();
		if ( $students->isEmpty() ) {
            janitor()->data( $cli->arg( "command" ), [
                "status" => 400,
                "message" => "No students."
            ] );
            return;
        }

		$cli->kirby()->impersonate( "kirby" );

		foreach ( $students as $student ) {
			try {
				$student->update( [
					"choices" => $workshops->shuffle()->limit( 3 )->toArray( fn($w) => $w->id() )
				] );
			} catch ( Exception $e ) {
				janitor()->data( $cli->arg( "command" ), [
					"status" => 400,
					"message" => "Failed to update choices for student {$student->nameOrEmail()}: {$e->getMessage()}."
				] );
				return;
			}
		}

		janitor()->data( $cli->arg( "command" ), [
			"status" => 200,
			"message" => "Success!",
			"reload" => true,
		] );
		return;
	}
];
