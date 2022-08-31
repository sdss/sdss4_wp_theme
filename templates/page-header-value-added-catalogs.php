<?php
$current_page = get_post(); 
global $single_id;
$single_id = rtrim ( get_query_var( 'vac_id' , '' ) , '/' );

global $isTest;
$isTest = ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') );
//$isTest = false;

//print_r(get_option("sdss_vacs"));
/*
if ( !empty( $single_id ) ) {
	$title = 'Value Added Catalog';
	if ( $vacs_data = get_option( 'sdss_vacs' ) ) {
		if ( array_key_exists( $single_id , $vacs_data ) ) {
			$title = ( $isTest) ?
				$vacs_data[ $single_id ]->title . " (ID: " . $vacs_data[ $single_id ]->id . ")" :
				$vacs_data[ $single_id ]->title ;
			 
		}
	}
} else {
	$title = 'Value Added Catalogs';
}
echo "<h1 id='overview'>$title</h1>"
*/
?>
