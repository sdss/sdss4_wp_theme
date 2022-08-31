<?php
the_content(); 

$project_data_json = @file_get_contents(  PATH_JSON . 'project.json' );
$project_data = json_decode( $project_data_json, true );

echo "<div class='sdss-wrapper'>";
echo "<h2>Full Member Institutions</h2>";
echo "<ul>";
foreach ($project_data['full_members'] as $thisrow) {
	//foreach ($this_project_data_container as $thisrow) {
	echo("<li>".$thisrow['title']."</li>");//echo "<li>".$x['title']."</li>";
	}
echo "</ul>";

echo "<h2>Associate Member Institutions</h2>";
echo "<ul>";
foreach ($project_data['associate_members'] as $thisrow) {
	//foreach ($this_project_data_container as $thisrow) {
	echo("<li>".$thisrow['title']."</li>");//echo "<li>".$x['title']."</li>";
	}
echo "</ul>";

echo "<h2>Participation Groups</h2>";
echo "<ul>";

foreach ($project_data['participations'] as $thisrow) {
	echo "<li><strong>".$thisrow['title']."</strong><ul>";
	foreach ($thisrow['affiliations'] as $this_affiliation) {
		echo "<li>".$this_affiliation['title']."</li>";
	}
	echo "</ul></li>";
}

echo "</ul>";

echo "<p>Last modified: ".$project_data['modified']."</p>";

echo "</div>";

//}
//print("<hr />");

//foreach ($project_data as $this_project_data_container) {
//	print_r($this_project_data_container);
//	foreach ($this_affiliation_container as $this_affiliation) {
		//foreach ($this_affiliation as $k => $v) {
			//echo "<p>".$k." : ".$v."</p>"; //print_r($this_affiliation);
			//}
		//echo "<p>&nbsp;</p>";
		//}
//}
//}

/*

$option  = get_option( 'sync_json_options' , false );
$error_level="info";
$msg=false;

if ( $option !== false ) {
	
	if ( $option[0][ "project-updating" ] == "true" ){
		$msg = "Affiliations are currently being updated. Please try again in a few minutes.";
		$error_level="warning";
	} else {
		$result = sdss_get_project_affiliations( false , 2 );
		if ( !$result ) {
			$msg = "No affiliations found.";
			$error_level="danger";			
		}  else {
			echo "<p class='modified'>Last modified: " . $option[0][ "affiliations-modified" ] . "</p>";
		}
	}
} else {
	$msg = "An internal error has occurred. ";
	$error_level="danger";
}
if ($msg) echo "<div class='alert alert-$error_level'>" . $msg . "</div>";

function sdss_get_project_affiliations( $return = false , $h2=2 ) {
	
	$h2 = "h" . intval($h2);

	// get the data from options 
	if ( !( ( $project_data = get_option( 'sdss_project' ) ) && 
		 ( $participations_data = get_option( 'sdss_participations' ) ) ) ) return false;

	$full_participation_list = '';
	foreach( $participations_data as $this_participation_id=>$this_participation ){

		$this_participation_list = '';
		foreach( $project_data as $this_project ){
			
			if ( $this_project['participation_id'] == $this_participation_id )
				$this_participation_list .= "<li>" . $this_project['title'] . "</li>\n";
		}
		$this_participation_list = "<strong>" . $this_participation['title'] . "</strong>\n<ul class='none'>\n" . $this_participation_list . "</ul>\n";
		$full_participation_list .= "<li>" . $this_participation_list . "</li>";
	}
	$result  = "<$h2>Participation Groups</$h2>\n<ul\n>" . $full_participation_list . "</ul>";

	$full_member_list = '';
	$associate_member_list = '';
	foreach( $project_data as $this_project ){
		if ( strcmp( "full_member" , $this_project['type']) ===0 ) $full_member_list .= "<li>" . $this_project['title'] . "</li>\n";
		if ( strcmp( "associate_member" , $this_project['type']) ===0 ) $associate_member_list.= "<li>" . $this_project['title'] . "</li>\n";
	}

	$result = "<$h2>Associate Member Institutions</$h2>\n<ul>\n" . $associate_member_list . "</ul>\n" . $result;
	$result = "<$h2>Full Member Institutions</$h2>\n<ul>\n" . $full_member_list . "</ul>\n" . $result;

	$result = "<div class='sdss-wrapper'>" . $result . "</div>";

	if ( $return ) return $result;
	
	echo $result;
	return true;
	
}
*/
?>