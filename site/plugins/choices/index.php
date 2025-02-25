<?php

Kirby::plugin('retreat/choices', [
  "siteMethods" => [
    "getChoices" => function() {
        $choices = [];
        $total_choices = [
          "title" => "Totals",
          "first" => 0,
          "second" => 0,
          "third" => 0,
          "total" => 0,
          "stats" => [
            "years" => [],
            "genders" => [],
            "average" => [],
          ],
          // "mfa"=> [],
        ];
        foreach ( $this->getWorkshops() as $workshop ) {
            $choices[ $workshop->id() ] = [
                "title" => $workshop->title()->toString(),
                "id" => $workshop->id(),
                "first" => 0,
                "second" => 0,
                "third" => 0,
                "stats" => [
                  "years" => [],
                  "genders" => [],
                  "average" => [],
                ],
                "total" => 0
            ];
        }
        foreach ( $this->getRelevantStudents() as $student ) {
            $student_year = $student->year()->value();
            $student_gender = $student->gender()->value();
            $student_average = $student->average()->toFloat();

            if ( $student_choices = $student->content()->choices()->toPages() ) {
                if ( $student_choices->count() === 0 ) continue;
                $idx = 0;
                $total_choices[ "total" ] += 1;
                if ( isset( $total_choices[ "stats" ][ "years" ][ $student_year ] ) ) {
                  $total_choices[ "stats" ][ "years" ][ $student_year ] += 1;
                } else {
                  $total_choices[ "stats" ][ "years" ][ $student_year ] = 1;
                }
                // if ( strpos( $student_year, "תואר שני" ) === 0 ) {
                //   $total_choices[ "mfa" ][] = $student->name()->toString();
                // }
                if ( isset( $total_choices[ "stats" ][ "genders" ][ $student_gender ] ) ) {
                  $total_choices[ "stats" ][ "genders" ][ $student_gender ] += 1;
                } else {
                  $total_choices[ "stats" ][ "genders" ][ $student_gender ] = 1;
                }
                $total_choices[ "stats" ][ "average" ][] = $student_average;
                foreach ( $student_choices as $student_choice ) {
                    switch ( $idx ) {
                        case 0:
                            $choices[ $student_choice->id() ][ "first" ] += 1;
                            $total_choices[ "first" ] += 1;
                            break;
                        case 1:
                            $choices[ $student_choice->id() ][ "second" ] += 1;
                            $total_choices[ "second" ] += 1;
                            break;
                        case 2:
                            $choices[ $student_choice->id() ][ "third" ] += 1;
                            $total_choices[ "third" ] += 1;
                            break;
                        }
                    $choices[ $student_choice->id() ][ "total" ] += 1;
                    if ( isset( $choices[ $student_choice->id() ][ "stats" ][ "years" ][ $student_year ] ) ) {
                      $choices[ $student_choice->id() ][ "stats" ][ "years" ][ $student_year ] += 1;
                    } else {
                      $choices[ $student_choice->id() ][ "stats" ][ "years" ][ $student_year ] = 1;
                    }
                    if ( isset( $choices[ $student_choice->id() ][ "stats" ][ "genders" ][ $student_gender ] ) ) {
                      $choices[ $student_choice->id() ][ "stats" ][ "genders" ][ $student_gender ] += 1;
                    } else {
                      $choices[ $student_choice->id() ][ "stats" ][ "genders" ][ $student_gender ] = 1;
                    }
                    $choices[ $student_choice->id() ][ "stats" ][ "average" ][] = $student_average;    
                    $idx += 1;
                }
            }
        }
        foreach ( $choices as &$choice ) {
          ksort( $choice[ "stats" ][ "years" ] );
          ksort( $choice[ "stats" ][ "genders" ] );
          if ( empty( $choice[ "stats" ][ "average" ] ) ) {
            $choice[ "stats" ][ "average" ] = 0;  
          } else {
            $choice[ "stats" ][ "average" ] = number_format( array_sum( $choice[ "stats" ][ "average" ] ) / array_reduce( $choice[ "stats" ][ "average" ], fn( $car, $itm ) => $car + ( empty( $itm ) ? 0 : 1 ), 0 ), 2 );
          }
        }
        $totals = array_column( $choices, "total" );
        array_multisort( $totals, SORT_DESC, $choices );
        ksort( $total_choices[ "stats" ][ "years" ] );
        ksort( $total_choices[ "stats" ][ "genders" ] );
        $total_choices[ "stats" ][ "average" ] = number_format( array_sum( $total_choices[ "stats" ][ "average" ] ) / array_reduce( $total_choices[ "stats" ][ "average" ], fn( $car, $itm ) => $car + ( empty( $itm ) ? 0 : 1 ), 0 ), 2 );
        $choices[ "zz_totals" ] = $total_choices;
        return $choices;
    },
  ],
  'areas' => [
    'choices' => [
      'label'   => 'Choice Ranking',
      'icon'    => 'star',
      'menu'    => true,
      // view route
      'views' => [
        require __DIR__ . '/views/choices.php'
      ]
    ]
  ]
]);

