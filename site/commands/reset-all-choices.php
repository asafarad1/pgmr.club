<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Reset choices for all students',
	'args' => [] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {

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
					"choices" => []
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
