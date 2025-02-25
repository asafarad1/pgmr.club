<?php

use Bnomei\Janitor;
use Kirby\CLI\CLI;
use Kirby\Toolkit\V;

function check_student_against_stats( $report, $student ) {
	if ( $report[ "total" ] < $report[ "limit" ] / 3 ) {
		return true;
	}
	switch ( $student->year()->value() ) {
		case "א'":
			if ( $report[ "year" ][ 0 ] > $report[ "limit" ] / 4 ) {
				return false;
			}
			break;
		case "ב'":
			if ( $report[ "year" ][ 1 ] > $report[ "limit" ] / 4 ) {
				return false;
			}
			break;
		case "ג'":
			if ( $report[ "year" ][ 2 ] > $report[ "limit" ] / 4 ) {
				return false;
			}
			break;
		case "ד'":
			if ( $report[ "year" ][ 3 ] > $report[ "limit" ] / 4 ) {
				return false;
			}
			break;
	}
	if ( $student->gender()->value() === "male" && $report[ "gender" ][ "male" ] > $report[ "limit" ] / 4 ) {
		return false;
	}
	return true;
}

function add_student_to_stats( $report, $student ) {
	$total_with_grades = array_sum( $report[ "year" ] ) - $report[ "year" ][ 0 ];
	switch ( $student->year()->value() ) {
		case "א'":
			$report[ "year" ][ 0 ] += 1;
			break;
		case "ב'":
			$report[ "year" ][ 1 ] += 1;
			break;
		case "ג'":
			$report[ "year" ][ 2 ] += 1;
			break;
		case "ד'":
			$report[ "year" ][ 3 ] += 1;
			break;
	}
	switch ( $student->gender()->value() ) {
		case "זכר":
			$report[ "gender" ][ "male" ] += 1;
			break;
		case "נקבה":
			$report[ "gender" ][ "female" ] += 1;
			break;
	}
	if ( $student->average()->toFloat() ) {
		$report[ "average" ] = $report[ "average" ] * ( $total_with_grades / ( $total_with_grades + 1 ) ) + $student->average()->toFloat() * ( 1 / ( $total_with_grades + 1 ) );
	}
	$report[ "total" ] += 1;
	return $report;
}

function get_workshop_stats( $workshop, $reset = false ) {
	$report = [
		"limit" => $workshop->getLimit(),
		"total" => 0,
		"year" => [ 0, 0, 0, 0 ],
		"average" => 0,
		"gender" => [ "male" => 0, "female" => 0 ],
	];
	if ( !$reset ) {
		$participants = $workshop->participants()->toUsers();
		foreach ( $participants as $participant ) {
			$report = add_student_to_stats( $report, $participant );
		}
	}
	return $report;
}

function flatten_report( $report, $w_id ) {
	$flattened = [
		"workshop" => [ $w_id ],
		"total" => $report[ "total" ],
		"year_a" => $report[ "year" ][ 0 ],
		"year_b" => $report[ "year" ][ 1 ],
		"year_c" => $report[ "year" ][ 2 ],
		"year_d" => $report[ "year" ][ 3 ],
		"male" => $report[ "gender" ][ "male" ],
		"female" => $report[ "gender" ][ "female" ],
		"average" => number_format( $report[ "average" ], 2),
	];
	return $flattened;
}

return [
	'description' => 'Assign students to workshops',
	'args' => [
		"reset" => [
			"prefix" => "r",
			"longPrefix" => "reset",
			"description" => "Re-assign unassigned students",
			"noValue" => true,
		]
	] + Janitor::ARGS,
	'command' => static function (CLI $cli): void {

		$alerts = [];

		$report = [
			"title" => "Assignment report " . date( "Y-m-d H:i" ),
			"total_first" => 0,
			"total_second" => 0,
			"total_third" => 0,
			"total_fourth" => 0,
			"total_random" => 0,
			"randoms" => [],
			"unassigned" => [],
		];

		$reset_assignments = $cli->arg( "reset" );
	
		$keys = option( "students.keys" );
		$assign_page = page( $cli->arg( 'page' ) );
		if ( $assign_page?->intendedTemplate()->name() !== "assign" ) {
            janitor()->data( $cli->arg( "command" ), [
                "status" => 400,
                "message" => "No assign page."
            ] );
            return;
        }
		$students = $reset_assignments ? $cli->kirby()->site()->getRelevantStudents() : $cli->kirby()->site()->getUnassignedStudents();
		if ( $students->isEmpty() ) {
            janitor()->data( $cli->arg( "command" ), [
                "status" => 400,
                "message" => "No students."
            ] );
            return;
        }

		$students = $students->shuffle();
	
		$order = [];
		foreach ( $assign_page->content()->order()->yaml() as $criterion ) {
			$order = array_merge( $order, array_values( $criterion ) );
		}
		$students = $students->sortBy( ...$order );
	
		$workshop_participants = [];
		$workshop_stats = [];
		foreach ( $cli->kirby()->site()->getWorkshops() as $workshop ) {
			$workshop_participants[ $workshop->id() ] = $reset_assignments ? [] : $workshop->participants()->yaml();
			$workshop_stats[ $workshop->id() ] = get_workshop_stats( $workshop, $reset_assignments );
		}
	
		$assignments = [];
	
		foreach ( $students as $student ) {
			if ( !$reset_assignments && $assignment = $student->getAssignment() ) {
				$assignments[ $student->id_num()->value() ] = $assignment->title()->toString();
				continue;
			}
			$choices = $student->content()->choices()->toPages();
			$idx = 0;
			foreach ( $choices as $choice ) {
				if ( count( $workshop_participants[ $choice->id() ] ) < $choice->getLimit() ) {
					if ( check_student_against_stats( $workshop_stats[ $choice->id() ], $student ) ) {
						$workshop_participants[ $choice->id() ][] = $student->uuid()->toString();
						$assignments[ $student->id_num()->value() ] = $choice->title()->toString();
						$workshop_stats[ $choice->id() ] = add_student_to_stats( $workshop_stats[ $choice->id() ], $student );
						if ( $idx === 0 ) {
							$report[ "total_first" ] += 1;
						} elseif ( $idx === 1 ) {
							$report[ "total_second" ] += 1;
						} elseif ( $idx === 2 ) {
							$report[ "total_third" ] += 1;
						} elseif ( $idx === 3 ) {
							$report[ "total_fourth" ] += 1;
						}
						break;	
					}
				}
				$idx += 1;
			}
		}
	
		if ( $assign_page->assign_random()->toBool() ) {
			$students_for_random = $students;
			if ( $exclude_from_random = $assign_page->exclude_from_random()->toStructure() ) {
				foreach ( $exclude_from_random as $exclude_row ) {
					$field_name = $exclude_row->name()->toString();
					$field_comparator = $exclude_row->comparator()->isEmpty() ? "same" : $exclude_row->comparator()->value();
					$field_value = $exclude_row->value()->toString();
					$students_for_random = $students_for_random->filter( fn( $s ) => ! V::$field_comparator( $s->$field_name()->value(), $field_value ) );
				}
			}
			foreach ( $students_for_random as $student ) {
				if ( empty( $assignments[ $student->id_num()->value() ] ) ) {
					foreach ( $cli->kirby()->site()->getWorkshops()->shuffle() as $choice ) {
						if ( count( $workshop_participants[ $choice->id() ] ) < $choice->getLimit() ) {
							if ( $assign_page->dont_check_random()->toBool() || check_student_against_stats( $workshop_stats[ $choice->id() ], $student ) ) {
								$workshop_participants[ $choice->id() ][] = $student->uuid()->toString();
								$assignments[ $student->id_num()->value() ] = $choice->title()->toString();
								$workshop_stats[ $choice->id() ] = add_student_to_stats( $workshop_stats[ $choice->id() ], $student );
								$report[ "total_random" ] += 1;
								$report[ "randoms" ][] = $student->uuid()->toString();
								break;
							}
						}
					}
				}
			}	
		}

		foreach ( $students as $student ) {
			if ( empty( $assignments[ $student->id_num()->value() ] ) ) {
				$assignments[ $student->id_num()->value() ] = "NO ASSIGNMENT";
				$report[ "unassigned" ][] = $student->uuid()->toString();
			}
		}

		$cli->kirby()->impersonate( "kirby" );
	
		foreach ( $workshop_participants as $w_id => $w_participants ) {
			try {
				page( $w_id )?->update( [
					"participants" => $w_participants
				] );
			} catch ( Exception $e ) {
				janitor()->data( $cli->arg( "command" ), [
					"status" => 400,
					"message" => "Failed to update participants for workshop $w_id: {$e->getMessage()}."
				] );
				return;
			}
		}

		$flattened_report = [];
		foreach ( $workshop_stats as $w_id => $r ) {
			$flattened_report[] = flatten_report( $r, $w_id );
		}
		$report[ "report" ] = $flattened_report;

		try {
			$assign_page->createChild( [
				"template" => "assign-report",
				"draft" => false,
				"slug" => "assign-report-" . date( "Y-m-d-H-i-s" ),
				"content" => $report
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
