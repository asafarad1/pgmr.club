<?php

return [
	'description' => 'Put users into kirby from CSV',
	'args' => [
		"csv" => [
			"description" => "The path to the CSV file",
			"required" => true,
		]
	],
	'command' => static function ($cli): void {
		$fp = fopen( $cli->arg( "csv" ), "r" );
		kirby()->impersonate( "kirby" );
		$header_row = fgetcsv( $fp );
		while ( $row = fgetcsv( $fp ) ) {
			$row = array_combine( $header_row, $row );
			if ( $existing_user = kirby()->users()->findBy( "email", strtolower( $row[ "email" ] ) ) ) {
				try {
					$existing_user = $existing_user->changeName( $row[ "fname" ] . " " . $row[ "lname" ] );
					$user = $existing_user->update( [
						"id_num" => $row[ "id" ],
						"fname" => $row[ "fname" ],
						"lname" => $row[ "lname" ],
						"tel" => $row[ "tel" ],
						"miluim" => $row[ "miluim" ],
						"average" => $row[ "average" ],
						"gender" => $row[ "gender" ],
						"year" => $row[ "year" ],
						"classroom" => $row[ "classroom" ],
						"studies_status" => $row[ "studies_status" ],
						"studio" => $row[ "studio" ],
						"curriculum" => $row[ "curriculum" ],
						"degree" => $row[ "degree" ],
					] );	
				} catch ( Exception $e ) {
					$cli->error( "Failed to update user " . $row[ "email" ] . ": " . $e->getMessage() );
				}
				if ( $user ) {
					$cli->success( "Updated user " . $row[ "email" ] . " successfully!" );
				}
			} else {
				try {
					$user = kirby()->users()->create( [
						"email" => $row[ "email" ],
						"role" => "student",
						"language" => "en",
						"name" => $row[ "fname" ] . " " . $row[ "lname" ],
						"content" => [
							"id_num" => $row[ "id" ],
							"fname" => $row[ "fname" ],
							"lname" => $row[ "lname" ],	
							"tel" => $row[ "tel" ],
							"miluim" => $row[ "miluim" ],
							"average" => $row[ "average" ],
							"gender" => $row[ "gender" ],
							"year" => $row[ "year" ],
							"classroom" => $row[ "classroom" ],
							"studies_status" => $row[ "studies_status" ],
							"studio" => $row[ "studio" ],
							"curriculum" => $row[ "curriculum" ],
						]
					] );
				} catch ( Exception $e ) {
					$cli->error( "Failed to create user " . $row[ "email" ] . ": " . $e->getMessage() );
				}	
				if ( $user ) {
					$cli->success( "Created user " . $row[ "email" ] . " successfully!" );
				}
			}
		}
	}
];
