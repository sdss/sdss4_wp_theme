<?php 
the_content(); 

$architects_data_json = @file_get_contents(  PATH_JSON . 'architects.json' );
$architects_data = json_decode( $architects_data_json, true );

echo '<div class="sdss-wrapper">';

foreach ($architects_data['architects'] as $thisrow) {
	echo "<p><strong>".$thisrow['name']."</strong> ".$thisrow['comment']."</p>";
}

echo "<p>Last modified: ".$architects_data['modified']."</p>";

echo "</div>";
/*$option  = get_option( 'sync_json_options' , false );
$error_level="info";
$msg=false;

if ( $option !== false ) {

	if ( ( $option[0][ "members-updating" ] ) ||
		( $option[0][ "architects-updating" ] ) ) {
			
		$msg = "Architects are currently being updated. Please try again in a few minutes.";
		$error_level="warning";
		
	} else {
		
		$members_data = get_option( 'sdss_members' );
		$architects_data = get_option( 'sdss_architects' );

		foreach ( $architects_data as $member_id => $data ) :  
			if ( array_key_exists( $member_id , $members_data ) )
			echo '<p><strong>' . $members_data[$member_id][ 'fullname' ] . '</strong> ';
			echo $data[ 'comment' ] . '</p>' . "\n";
		endforeach; 
		echo "<p class='modified'>Last modified: " . $option[0][ "architects-modified" ] . "</p>";
	}

} else {
	$msg = "An internal error has occurred. ";
	$error_level="danger";
}
if ( $msg ) echo "<div class='alert alert-$error_level'>" . $msg . "</div>";

*/
?>