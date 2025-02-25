<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Message students with their assignments',
	'args' => [] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {

		$alerts = [];
		$notices = [];

		$page = page( $cli->arg( 'page' ) );
		$assign_page = page( "assign" );

		$workshops = $page?->intendedTemplate()->name() === "workshop" ?  new \Kirby\Cms\Pages( [ $page ] ) : $cli->kirby()->site()->children()->filterBy( "intendedTemplate", "workshop" );

		$student_template = $cli->kirby()->site()->assignment_email()->toString();
		$assistant_template = $cli->kirby()->site()->assistant_email()->toString();

		$cli->kirby()->impersonate( "kirby" );

		foreach ( $workshops as $workshop ) {
			$drive_link = $workshop->drive_link()->toUrl();
			$workshop_assistant = $workshop->assistant()->toUser();
			foreach ( $workshop->participants()->toUsers() as $participant ) {
				$template = $workshop_assistant?->id() === $participant->id() ? $assistant_template : $student_template;
				try {
					$email_status = $cli->kirby()->email( [
						"from" => "info@retreat2024.art",
						"replyTo" => "vc2@bezalel.ac.il",
						"to" => $participant->email(),
						"subject" => $cli->kirby()->site()->title()->toString() . ": השיבוץ שלך",
						"body" => [
							"text" => str_replace( 
								[ "{name}", "{workshop_name}", "{workshop_requirements}", "{google_drive}" ],
								[ $participant->nameOrEmail(), $workshop->title()->toString(), strip_tags_keep_nls( $workshop->requirements()->toString() ), $drive_link ],
								strip_tags_keep_nls( $template )
							),
							"html" => str_replace( 
								[ "{name}", "{workshop_name}", "{workshop_requirements}", "{google_drive}" ], 
								[ $participant->nameOrEmail(), $workshop->title()->toString(), $workshop->requirements()->toString(), $drive_link ],
								"<div dir='rtl'>$template</div>"
							)
						],
					] );
				} catch ( Exception $e ) {
					$alerts[] = [
						"workshop" => $workshop->id(),
						"user" => $participant->id(),
						"alert" => "Error sending email to " . $participant->email() . ": " . $e->getMessage()
					];
				}
				if ( $email_status?->isSent() ) {
					$notices[] = [
						"workshop" => $workshop->id(),
						"user" => $participant->id(),
						"notice" => "Assignment email sent to " . $participant->email() . " successfully!"
					];
				} else {
					$alerts[] = [
						"workshop" => $workshop->id(),
						"user" => $participant->id(),
						"alert" => "Sending email to " . $participant->email() . "failed."
					];
				}
			}
		}
	
		try {
			$assign_page->createChild( [
				"template" => "message-report",
				"draft" => false,
				"slug" => "message-assignments-report-" . date( "Y-m-d-H-i-s" ),
				"content" => [
					"title" => "Message Assignments report " . date( "Y-m-d H:i:s" ),
					"total_sent" => count( $notices ),
					"total_failed" => count( $alerts ),
					"notices" => $notices,
					"alerts" => $alerts,
				]
			] );
		} catch ( Exception $e ) {
			janitor()->data( $cli->arg( "command" ), [
				"status" => 400,
				"message" => "Failed to generate report: {$e->getMessage()}."
			] );
			return;
		}
	
		janitor()->data( $cli->arg( "command" ), [
			"status" => 200,
			"message" => "Success!",
		] );
		return;
	}
];
