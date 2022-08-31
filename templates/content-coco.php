<?php 
the_content(); 

$coco_data_json = @file_get_contents(  PATH_JSON . 'coco.json' );
$coco_data = json_decode( $coco_data_json, true );

//print_r($coco_data);

echo '<div class="sdss-wrapper">';
echo "<h2>The Collaboration Council</h2>\n";
echo "<p>&nbsp;</p>";

echo "<p>Chair (and SDSS-IV Spokesperson): <strong>" . $coco_data['spokesperson']['fullname'] . "</strong></p>";
//print_r($coco_data['spokesperson']);
//	if ( $affil_title = get_affiliation( $this_spokesperson, $affiliations_data, $participations_data ) )
//		echo " ( $affil_title ).";
//	echo"</p>\n";

echo "<div class='center-block'><em>Current members of the Collaboration Council and member institutions they represent.</em></div>";
echo "<dl class='dl-horizontal dl-horizontal-third'>\n";
foreach ($coco_data['coco'] as $thisrow) {
	echo "<dt>".$thisrow['fullname'].":</dt>";
	echo "<dd>".$thisrow['institute']."</dd>";
	//print_r($thisrow)."</dd>";
}
echo "</dl>";

echo "<p>&nbsp;<br/>";
echo "Council Representative for all Institutions with less than 3 slots: <strong>" . $coco_data['lessthan3']['fullname'] . "</strong>";
echo"</p>\n";

echo "<p>Last modified: ".$coco_data['modified']."</p>";

echo '</div>';



// OLD CODE FROM BONNIE SOUTER
//$jsonpath = get_option('path_json', false);
//echo 'json path'.$path_json.'<br /><br />';
//echo path_json;
//$coco = get_option( 'sdss_coco' , false );
//echo $coco;
/*
if ( $option !== false ) {

	if ( ( $option[0][ "members-updating" ] == "true" ) 
		|| ( $option[0][ "coco-updating" ] == "true" )
		|| ( $option[0][ "project-updating" ] == "true" )
		|| ( $option[0][ "roles-updating" ] == "true" )	){
		
		$msg = "CoCo is currently being updated. Please try again in a few minutes.";
		$error_level="warning";
		
	} else {
		$result = show_coco(  );
		if ( !$result) {
			$msg = "An internal error has occurred. ";
			$error_level='danger';
		} else {
			echo "<p class='modified'>Last modified: " . $option[0][ "coco-modified" ] . "</p>";
		}
	}
} else {
	$msg = "An internal error has occurred. ";
	$error_level="danger";
}
if ( $msg ) echo "<div class='alert alert-$error_level'>" . $msg . "</div>";


function show_coco(  ){

	if ( !( $members_data = get_option( 'sdss_members' , false ) ) ||
		!( $coco_data = get_option( 'sdss_coco' , false ) ) ||
		!( $roles_data = get_option( 'sdss_roles' , false ) ) ||
		!( $affiliations_data = get_option( 'sdss_project' , false ) ) ||
		!( $participations_data = get_option( 'sdss_participations' , false ) ) ) {
			return false;
	}
	echo '<div class="sdss-wrapper">';

	//Project Spokesperson
	$this_spokesperson = $coco_data[ 'spokesperson' ];
	unset( $coco_data[ 'spokesperson' ] );

	// <3 Representative
	$lessthan3 = $coco_data[ 'lessthan3' ];
	unset( $coco_data[ 'lessthan3' ] );

	// Fail Gracefully
	if ( empty( $coco_data ) ) {
		//echo "<div class='label label-warning'>No data found</div>";
		return false;
	}

	echo "<h2>The Collaboration Council</h2>\n";
	echo "<p>&nbsp;<br/>";
	echo "Chair (and SDSS-IV Spokesperson) <strong>" . $members_data[ $this_spokesperson[ 'member_id' ] ][ 'fullname' ] . "</strong>";
	if ( $affil_title = get_affiliation( $this_spokesperson, $affiliations_data, $participations_data ) )
		echo " ( $affil_title ).";
	echo"</p>\n";

	echo "<div class='center-block'><em>Current members of the Collaboration Council and member institutions they represent.</em></div>";


	echo "<dl class='dl-horizontal dl-horizontal-third'>\n";
		foreach($coco_data as $this_member_id=>$this_coco){
		
			echo "<dt>" . $members_data[ $this_member_id ][ 'fullname' ] . ": </dt>\n";
			echo "<dd>" . get_affiliation( $this_coco, $affiliations_data, $participations_data ) . "</dd>\n";

		}
	echo "</dl>\n";

	echo "<p>&nbsp;<br/>";
	echo "Council Representative for all Institutions with less than 3 slots <strong>" . $members_data[ $lessthan3[ 'member_id' ] ][ 'fullname' ] . "</strong>";
	if ( $affil_title = get_affiliation( $lessthan3, $affiliations_data, $participations_data ) )
		echo " ( $affil_title ).";
	echo"</p>\n";

	echo '</div>';
	
	return true;

}

function get_affiliation( $this_coco, $affiliations_data, $participations_data ){

	if ( empty( $this_coco[ 'affiliation_id' ] ) ) {
		if ( empty( $this_coco[ 'participation_id' ] ) ) 
			return false;

		if ( empty( $participations_data[ $this_coco[ 'participation_id' ] ][ 'title' ] ) ) 
			return false;
	
		return $participations_data[ $this_coco[ 'participation_id' ] ][ 'title' ];
	
	}
		
	if ( empty( $affiliations_data[ $this_coco[ 'affiliation_id' ] ][ 'title' ] ) ) 
		return false;

	return $affiliations_data[ $this_coco[ 'affiliation_id' ] ][ 'title' ];
		
}
*/