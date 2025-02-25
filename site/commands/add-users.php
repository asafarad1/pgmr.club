<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
	'description' => 'Add/updated users into kirby from CSV',
	'args' => [] + Janitor::ARGS,
	'command' => static function ($cli): void {
		$add_users_page = page( $cli->arg( 'page' ) );
		if ( ! ( $new_role = $add_users_page?->content()->role()->value() ) ) {
			$new_role = "student";
		}

		$mapping = $add_users_page->mapping()->toStructure();

		$alerts = [];
		$updated = [];
		$added = [];

		if ( $csv_file = $add_users_page->content()->csv()->toFile() ) {
			$fp = fopen( $csv_file->realpath(), "r" );
			kirby()->impersonate( "kirby" );
			$header_row = fgetcsv( $fp );
			foreach ( $header_row as &$header_cell ) {
				$header_cell = str_replace( (chr(0xEF) . chr(0xBB) . chr(0xBF)), "", $header_cell );
				if ( $new_header_cell = $mapping->findBy( "name", $header_cell )?->content()->field()->value() ) {
					$header_cell = $new_header_cell;
				} else {
					janitor()->data( $cli->arg( "command" ), [
						"status" => 400,
						"message" => "Field $header_cell isn't mapped."
					] );
					return;
				}
			}
			if ( !in_array( "email", $header_row ) ) {
				janitor()->data( $cli->arg( "command" ), [
					"status" => 400,
					"message" => "No email field in table."
				] );
				return;
			}
			while ( $row = fgetcsv( $fp ) ) {
				$row = array_combine( $header_row, $row );
				if ( $existing_user = kirby()->users()->findBy( "email", strtolower( $row[ "email" ] ) ) ) {
					try {
						if ( !empty( $row[ "fname" ] ) && !empty( $row[ "lname" ] ) ) {
							$existing_user = $existing_user->changeName( $row[ "fname" ] . " " . $row[ "lname" ] );
						}
						$existing_user = $existing_user->changeRole( $new_role );
						$user = $existing_user->update( $row );
					} catch ( Exception $e ) {
						$alerts = [
							"email" => $row[ "email" ],
							"alert" => $e->getMessage()
						];
					}
					if ( $user ) {
						$updated[] = $user->uuid()->toString();
					}
				} else {
					$create_data = [
						"email" => $row[ "email" ],
						"role" => $new_role,
						"language" => "en",
						"content" => $row
					];
					if ( !empty( $row[ "fname" ] ) && !empty( $row[ "lname" ] ) ) {
						$create_data[ "name" ] = $row[ "fname" ] . " " . $row[ "lname" ];
					} elseif ( !empty( $row[ "name" ] ) ) {
						$create_data[ "name" ] = $row[ "name" ];
					}
					try {
						$user = kirby()->users()->create( $create_data );
					} catch ( Exception $e ) {
						$alerts = [
							"email" => $row[ "email" ],
							"alert" => $e->getMessage()
						];
					}	
					if ( $user ) {
						$added[] = $user->uuid()->toString();
					}
				}
			}	
		}
		
		fclose( $fp );

		try {
			$add_users_page->createChild( [
				"template" => "add-users-report",
				"draft" => false,
				"slug" => "add-users-report-" . date( "Y-m-d-H-i-s" ),
				"content" => [
					"title" => "Add users report " . date( "Y-m-d H:i:s" ),
					"updated" => $updated,
					"added" => $added,
					"alerts" => $alerts
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
			"reload" => true,
		] );
		return;
		
	}
];
