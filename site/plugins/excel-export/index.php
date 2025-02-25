<?php

require_once __DIR__ . "/vendor/autoload.php";

use Shuchkin\SimpleXLSXGen;

function export_to_excel( $filename, $workshops, $columns = [] ) {

    if ( empty( $columns ) ) {
        $columns = option( "export.keys" );
    }

    $columns[ "assignment" ] = "שיבוץ";
    $columns[ "comment" ] = "הערה";

    $idx = 0;

    foreach ($workshops as $workshop) {

        $sheet_name = substr( $workshop->slug(), 0, 31 );
        // if ( $idx > 0 ) break;
    
        $header_row = [];
        // Write column headers
        foreach (array_values( $columns ) as $colIndex => $colName) {
            $header_row[] = $colName;
        }
        $sheet_data = [ $header_row ];
    
        // Write rows
        $participants = $workshop->participants()->toUsers();
        if ( empty( $participants ) ) continue;

        $idx_row = 0;
        foreach ($participants as $participant) {
            $sheet_row = [];
            // if ( $idx_row > 0 ) break;
            foreach ( array_keys( $columns ) as $colIndex => $field_name ) {
                $choices = $participant->choices()->toPages()?->toArray( fn( $w ) => $w->title()->toString() );
                if ( $field_name === "assignment" ) {
                    $cellValue = $workshop->title()->toString();
                } elseif ( $field_name === "comment" ) {
                    if ( $choices ) {
                        if ( in_array( $workshop->title()->toString(), $choices ) ) {
                            $cellValue = "קיבל/ה עדיפות " . ( array_search( $workshop->title()->toString(), array_values( $choices ) ) + 1 );
                        } else {
                            $cellValue = "קיבל/ה שיבוץ אקראי.";
                        }
                    } else {
                        $cellValue = "לא בחר/ה ושובצ/ה אקראית.";
                    }
                } elseif ( $field_name === "email" ) {
                    $cellValue = $participant->email();
                } else {
                    $cellValue = $participant->$field_name()?->toString() ?? "";
                }
                $sheet_row[] = $cellValue;
                $idx_row += 1;
            }
            $sheet_data[] = $sheet_row;
        }
        
        $sheets[ $sheet_name ] = $sheet_data;
        $idx += 1;

    }

    $xlsx = new SimpleXLSXGen();
    foreach ( $sheets as $name => $data ) {
        $xlsx->addSheet( $data, $name );
    }

    $exports_dir = kirby()->root( "assets" ) . "/exports";
    $exports_url = kirby()->url() . "/assets/exports";

    try {
        if ( !file_exists( $exports_dir ) ) {
            mkdir( $exports_dir );
        }
        $xlsx->saveAs( $exports_dir . "/" . $filename );
        return $exports_url . "/" . $filename;
    } catch ( Exception $e ) {
        return false;
    }
    
}

Kirby::plugin( "retreat/excel-export", [
    "siteMethods" => [
        "exportWorkshops" => function() {
            return export_to_excel( "workshops-" . date( "Y-m-d-H-i-s" ) . ".xlsx", $this->getWorkshops() );
        }
    ]
] );