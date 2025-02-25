<?php

$csv_files = [
    "bezalel-students-a.csv",
    "bezalel-students-b.csv",
    "bezalel-students-c.csv",
    "bezalel-students-d.csv",
    "bezalel-students-mdes.csv",
];

$keys_translations = [
    "זיהוי" => "id",
    "שם משפחה" => "lname",
    "שם פרטי" => "fname",
    "מילואים- חרבות ברזל תשפ\"ד" => "miluim",
    "תוכנית לימודים" => "curriculum",
    "שנה" => "year",
    "כיתה" => "classroom",
    "סטטוס" => "studies_status",
    "דוא\"ל" => "email",
    "מין" => "gender",
    "טלפון נייד" => "tel",
    "ממוצע" => "average",
    "סטודיו" => "studio",
    "תואר" => "degree",
];

$csvs = [];
$unified = [];

function row_is_full( $row ) {
    return count( $row ) === count( array_filter( $row, fn($r) => !empty($r) ) );
}

function get_csv_data( $filename ) {
    $data = [];
    $fp = fopen( $filename, "r" );
    while ( ( $header_row = array_map( fn( $v ) => trim( $v, " \n\r\t\v\0\u{feff}"), fgetcsv( $fp ) ) ) && !row_is_full( $header_row ) ) { }
    while ( $row = fgetcsv( $fp ) ) {
        $data[] = array_combine( $header_row, $row );
    }
    fclose( $fp );
    return $data;
}

function combine_data( $arrays ) {
    global $keys_translations;
    $keys = [];
    $data = [];
    foreach ( $arrays as $arr ) {
        $keys = array_merge( $keys, array_keys( $arr[ 0 ] ) );
    }
    $keys = array_unique( $keys );
    foreach ( $arrays as $arr ) {
        foreach ( $arr as $row ) {
            $new_row = [];
            foreach ( $keys_translations as $key => $translation ) {
                if ( !isset( $row[ $key ] ) ) {
                    $new_row[ $translation ] = "";
                } else {
                    $new_row[ $translation ] = $row[ $key ];
                }
            }
            $data[] = $new_row;
        }
    }
    return $data;
}

function sort_data( $data ) {
    shuffle( $data );
    $year = array_column( $data, "year" );
    $average = array_column( $data, "average" );
    $miluim = array_column( $data, "miluim" );
    array_multisort( $year, SORT_DESC, $miluim, SORT_DESC, $average, SORT_DESC, $data );
    return $data;
}

function plot_table( $arr ) {
    
    ?>
    <table>
        <thead>
            <tr>
                <?php foreach ( $arr[ 0 ] as $key => $value ) : ?>
                    <th>
                        <?=$key ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $arr as $row ) : ?>
                <tr>
                <?php foreach ( $row as $value ) : ?>
                    <td><?=$value ?></td>
                <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}

function save_csv( $data ) {
    $fp = fopen( "students.csv", "w" );
    fputcsv( $fp, array_keys( $data[ 0 ] ) );
    foreach ( $data as $row ) {
        fputcsv( $fp, $row );
    }
    fclose( $fp );
}

foreach ( $csv_files as $filename ) {
    $csvs[] = get_csv_data( $filename );
}

$unified = combine_data( $csvs );
$unified = sort_data( $unified );

plot_table( $unified );
save_csv( $unified );
