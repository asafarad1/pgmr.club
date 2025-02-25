<?php

function strip_tags_keep_nls( $str ) {
	return strip_tags( str_replace( [ "</p>", "<br>" ], [ "</p>\n", "<br>\n" ], $str ) );
}

class StudentUser extends \Kirby\Cms\User {

    public function getAssignment() {
        return site()->getWorkshops()->filter( fn($w) => in_array( $this->uuid()->toString(), $w->participants()->yaml() ) )->first();
    }

    public function isRelevant() {
        $checks = option( "students.irrelevant", [] );
        return array_reduce( array_keys( $checks ) , fn($carry, $item) => $carry && !in_array( $this->$item()->value(), $checks[ $item ] ), true );
    }

}

Kirby::plugin( "retreat/helpers", [
    "siteMethods" => [
        "getWorkshops" => function() {
            return $this->children()->filterBy( "intendedTemplate", "workshop" );
        },
        "getRelevantStudents" => function() {
            return kirby()->users()->filterBy( "role", "student" )->filter( fn($u) => $u->isRelevant() );
        },
        "getUnassignedStudents" => function() {
            return $this->getRelevantStudents()->filter( fn( $s ) => !$s->getAssignment() );
        },
        "totalSeats" => function() {
            $total = 0;
            foreach ( $this->getWorkshops() as $ws ) {
                $total += $ws->getLimit();
            }
            return $total;
        },
        "getAllParticipants" => function() {
            $participants = new \Kirby\Cms\Users( [] );
            foreach ( $this->getWorkshops() as $ws ) {
                $participants = $participants->add( $ws->participants()->toUsers() );
            }
            return $participants;
        },
        "totalParticipants" => function() {
            $total = 0;
            foreach ( $this->getWorkshops() as $ws ) {
                $total += $ws->participants()->toUsers()->count();
            }
            return $total;
        },
        "totalAnsweredFeedback" => function() {
            $total = 0;
            foreach ( $this->getAllParticipants() as $participant ) {
                if ( $participant->content()->handed_feedback()->toBool() ) {
                    $total += 1;
                }
            }
            return $total;
        },
        "totalSelected" => function() {
            $total = 0;
            foreach ( $this->getRelevantStudents() as $student ) {
                if ( $student->content()->choices()->isNotEmpty() ) $total += 1;
            }
            return $total;
        },
        "totalUnassigned" => function() {
            return $this->getUnassignedStudents()->count();
        }
    ],
    "userModels" => [
        "student" => StudentUser::class
    ]
] );