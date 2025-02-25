<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Message workshop leaders with their students contact list.',
	'args' => [] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {

		$alerts = [];
		$notices = [];

		$page = page( $cli->arg( 'page' ) );
		$assign_page = page( "assign" );

		$workshops = $page?->intendedTemplate()->name() === "workshop" ?  new \Kirby\Cms\Pages( [ $page ] ) : $cli->kirby()->site()->children()->filterBy( "intendedTemplate", "workshop" );

		$template = $cli->kirby()->site()->teacher_email()->toString();

		$cli->kirby()->impersonate( "kirby" );

		foreach ( $workshops as $workshop ) {
			$assistant = "";
			$list = "<table>";
			foreach ( $workshop->participants()->toUsers()?->sortBy( "lname", "asc" ) as $participant ) {
				$list .= "<tr><td>" . $participant->name()->toString() . " </td><td>" . $participant->email() . " </td><td>" . $participant->tel()->toString() . " </td></tr>\n";
				if ( $workshop->assistant()->toUser()?->id() === $participant->id() ) {
					$assistant = "<table><tr><td>" . $participant->name()->toString() . " </td><td>" . $participant->email() . " </td><td>" . $participant->tel()->toString() . " </td></tr></table>\n";
				}
			}
			$list .= "</table>";

			$drive_link = $workshop->drive_link()->toUrl();
			$teacher_idx = 0;

			foreach ( $workshop->teachers_users()->toUsers() as $teacher ) {
				
				if ( $teacher_idx > 0 && $workshop->second_drive_link()->isNotEmpty() ) {
					$drive_link = $workshop->second_drive_link()->toUrl();
				}

				try {
					$email_status = $cli->kirby()->email( [
						"from" => "info@retreat2024.art",
						"replyTo" => "vc2@bezalel.ac.il",
						"to" => $teacher->email(),
						"subject" => $cli->kirby()->site()->title()->toString() . ": עדכונים לגבי הסדנה שלך",
						"body" => [
							"text" => str_replace( 
								[ "{name}", "{workshop_name}", "{participants_list}", "{assistant_details}", "{google_drive}" ], 
								[ $teacher->nameOrEmail(), $workshop->title()->toString(), strip_tags_keep_nls( $list ), strip_tags_keep_nls( $assistant ), $drive_link ],
								strip_tags_keep_nls( $template )
							),
							"html" => str_replace( 
								[ "{name}", "{workshop_name}", "{participants_list}", "{assistant_details}", "{google_drive}" ], 
								[ $teacher->nameOrEmail(), $workshop->title()->toString(), $list, $assistant, $drive_link ],
								"<div dir='rtl'>$template</div>"
							)
						],
					] );
				} catch ( Exception $e ) {
					$alerts[] = [
						"workshop" => $workshop->id(),
						"user" => $teacher->id(),
						"alert" => "Error sending email to " . $teacher->email() . ": " . $e->getMessage()
					];
				}
				if ( $email_status?->isSent() ) {
					$notices[] = [
						"workshop" => $workshop->id(),
						"user" => $teacher->id(),
						"notice" => "Assignment email sent to " . $teacher->email() . " successfully!"
					];
				} else {
					$alerts[] = [
						"workshop" => $workshop->id(),
						"user" => $teacher->id(),
						"alert" => "Sending email to " . $participant->email() . "failed."
					];
				}	
				$teacher_idx += 1;
			}
		}
	
		try {
			$assign_page->createChild( [
				"template" => "message-report",
				"draft" => false,
				"slug" => "message-teachers-report-" . date( "Y-m-d-H-i-s" ),
				"content" => [
					"title" => "Message Teachers report " . date( "Y-m-d H:i:s" ),
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
