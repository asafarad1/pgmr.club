<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Message students with requests for feedback',
	'args' => [] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {

		$alerts = [];
		$notices = [];

		$page = page( $cli->arg( 'page' ) );
		$assign_page = page( "assign" );

		$workshops = $page?->intendedTemplate()->name() === "workshop" ?  new \Kirby\Cms\Pages( [ $page ] ) : $cli->kirby()->site()->children()->filterBy( "intendedTemplate", "workshop" );

		$template = $cli->kirby()->site()->feedback_email()->toString();

		$cli->kirby()->impersonate( "kirby" );

		foreach ( $workshops as $workshop ) {
			foreach ( $workshop->participants()->toUsers() as $participant ) {
				try {
					$email_status = $cli->kirby()->email( [
						"from" => "info@retreat2024.art",
						"replyTo" => "vc2@bezalel.ac.il",
						"to" => $participant->email(),
						"subject" => $cli->kirby()->site()->title()->toString() . ": ספרו לנו איך היה",
						"body" => [
							"text" => str_replace( 
								[ "{name}" ], 
								[ $participant->nameOrEmail() ],
								strip_tags_keep_nls( $template )
							),
							"html" => str_replace( 
								[ "{name}" ], 
								[ $participant->nameOrEmail() ],
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
				"slug" => "message-feedback-report-" . date( "Y-m-d-H-i-s" ),
				"content" => [
					"title" => "Message Feedback Requests report " . date( "Y-m-d H:i:s" ),
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
