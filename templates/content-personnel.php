<?php 
the_content(); 

$debug = false;
$fullname_source = 'members';   // "members" gets fullames from members.json; "roles" gets fullnames from roles.json (per Jordan & Joel); if neither, name is "NAME NOT FOUND"

$leaders_data_json = @file_get_contents(  PATH_JSON . 'leaders.json' );
$leaders_data = json_decode( $leaders_data_json, true );

$roles_data_json = @file_get_contents(  PATH_JSON . 'roles.json' );
$roles_data = json_decode( $roles_data_json, true );

$members_data_json = @file_get_contents(  PATH_JSON . 'members.json' );
$members_data = json_decode( $members_data_json, true );

$mc_data_json = @file_get_contents(  PATH_JSON . 'mc.json' );
$mc_data = json_decode( $mc_data_json, true);

//print_r($mc_data['mc']);

if ($debug) {
	echo "<h2>Current page encoding: ".mb_internal_encoding()."</h2>";
	echo "<h2>File encoding before conversion: ".mb_detect_encoding($leaders_data_json)."</h2>";
	//$leaders_data_json = mb_convert_encoding($leaders_data_json, 'UTF-8', mb_detect_encoding($leaders_data_json));
	$leaders_data_json = mb_convert_encoding($leaders_data_json, 'UTF-8', mb_detect_encoding($leaders_data_json, 'UTF-8, ISO-8859-1', true));
	echo "<h2>File encoding after conversion: ".mb_detect_encoding($leaders_data_json)."</h2>";
}



#echo "<div class='sdss-wrapper'>";

$listlevel = 0;

foreach ($roles_data['roles'] as $thisrow) {
	if ($listlevel == 0) {
		echo "<ul>";
		if ($debug) echo "<li>UL</li>";
		$listlevel = 1;
	} elseif ($listlevel < $thisrow['level']) {
		echo "<ul>";
		if ($debug) echo "<li>UL</li>";
		if ($debug) print('<li>GOING DOWN...</li>');
		$listlevel = $thisrow['level'];
	} elseif ($listlevel > $thisrow['level']) {
		while ($listlevel > $thisrow['level']) {
			if ($debug) echo "<li>listlevel = ".$listlevel."; lv = ".$thisrow['level']."; GOING UP...</li>";
			echo "</ul>";
			if ($debug) echo "<li>/UL</li>";
			$listlevel = $listlevel - 1;
		}
	}
	echo "<li>";
	if ($debug) echo "listlevel = ".$listlevel."; lv = ".$thisrow['level']."; ";
	if ($debug)	{
		for ($i = 0; $i < $thisrow['level']; $i++) {
			echo "&nbsp;&nbsp;li-";
		}
	}
	echo $thisrow['role'];
	if ($debug)	echo " (role_id = ".$thisrow['role_id'].")";
	echo ": <strong>";
	$the_member_ids = searchForRoleID($thisrow['role_id'], $leaders_data['leaders'], $debug);
	$names_from_roles_array = namesFromRolesArray($thisrow['role_id'], $leaders_data['leaders'], $debug);
	//if ($debug) echo " names: ".count($thepeople).": ";

	for ($i = 0; $i <= count($the_member_ids) - 1; $i++) {
		if ($i > 0) echo ", ";
		$this_member_id = $the_member_ids[$i];
		$this_name = searchForMemberID($this_member_id, $members_data['members'], $debug);

		// Special case fixes - see Mike Blanton email of 2019-12-03
		if ($this_member_id == 609) $this_name = "Bruce A. Gillespie"; 
		if ($this_member_id == 108) $this_name = "Ben Harris"; 

		$this_name_from_roles_array = $names_from_roles_array[$i];
		
		if ($fullname_source == 'members') {
			echo $this_name;
		} elseif ($fullname_source == 'roles') {
			echo $this_name_from_roles_array;
		} else {
			echo "NAME NOT FOUND";
		}

		if ($debug) echo " (member_id = ".$this_member_id.")";
		$this_member_is_mc = isMC($this_member_id, $mc_data['mc'], $debug);
		if ($this_member_is_mc && $this_name <> "VACANT") echo "*";
	}
	//echo $mctext;
	echo "</strong>";

	//print_r($thisrow);
	echo "</li>";
}
if ($debug) echo "<li>listlevel = ".$listlevel."</li>";
while ($listlevel > 0) {
	if ($debug) echo "<li>/UL</li>";
	echo "</ul>";
	$listlevel = $listlevel - 1;
}

$leaders_last_modified = $leaders_data['modified'];
$roles_last_modified = $roles_data['modified'];
$members_last_modified = $members_data['modified'];
$mc_last_modified = $mc_data['modified'];

if ($debug) {
	echo "Leaders last modified: ".$leaders_last_modified."<br />";
	echo "Roles last modified: ".$leaders_last_modified."<br />";
	echo "Members last modified: ".$leaders_last_modified."<br />";
	echo "MC last modified: ".$leaders_last_modified."<br />";
	echo "<p>&nbsp;</p>";
}

echo "<p>Last modified: ".max($leaders_last_modified, $roles_last_modified, $members_last_modified, $mc_last_modified)."</p>";
/*
if ($leaders_last_modified >= $roles_last_modified) {
		echo "<p>Leaders last modified: ".$leaders_last_modified."</p>";
	} else {
		echo "<p>Roles last modified: ".$roles_last_modified."</p>";
	}*/


function searchForRoleID($id, $array, $debug_in_fcn) {
	$returnArray = Array();
	//if ($debug_in_fcn) echo "role_id = ".$id."...searching...";
	foreach ($array as $thisrow) {
		if ($thisrow['role_id'] === $id && $thisrow['current'] == 1) {
			array_push($returnArray, $thisrow['member_id']); //$thisrow['fullname']);
		}
	}
	if (count($returnArray) == 0) {
		if ($debug_in_fcn) {
			array_push($returnArray, "NOT FOUND!!!!");
		} else {
			array_push($returnArray, "");
		}
	}
	return $returnArray;
}

function searchForMemberID($id, $array, $debug_in_fcn) {
	foreach ($array as $thisrow) {
		if ($thisrow['member_id'] == $id) {
			//if ($debug_in_fcn) echo "XXX".$thisrow['fullname']."XXX";
			return $thisrow['fullname'];
		}
	}
	return "";//"VACANT";
}

function namesFromRolesArray($id, $array, $debug_in_fcn) {
	$returnArray = Array();
	foreach ($array as $thisrow) {
		if ($thisrow['role_id'] === $id && $thisrow['current'] == 1) {
			array_push($returnArray, $thisrow['fullname']);
		}
	}
	if (count($returnArray) == 0) {
		if ($debug_in_fcn) {
			array_push($returnArray, "NOT FOUND!!!!");
		} else {
			array_push($returnArray, "");
		}
	}
	return $returnArray;
}

function isMC($id, $array, $debug_in_fcn) {
	foreach ($array as $thisrow) {
		if ($thisrow['member_id'] == $id) {
			return true;
		}
	}
	//echo "<p color='red'><strong>MEMBER ID = ".$id." not found in array!</strong></p>";
	return false;
}



#echo "</div>";


/*
echo '<div class="sdss-wrapper">';
$option  = get_option( 'sync_json_options' , false );
$error_level="info";
$msg=false;

if ( $option !== false ) {

	if ( ( $option[0][ "leaders-updating" ] == "true" ) 
		|| ( $option[0][ "members-updating" ] == "true" )
		|| ( $option[0][ "roles-updating" ] == "true" )	){
		
		$msg = "Personnel are currently being updated. Please try again in a few minutes.";
		$error_level="warning";
		
	} else {
		
		// get the data from options 
		if ( !( $leaders_data = get_option( 'sdss_leaders' , false ) ) ||
			!( $members_data = get_option( 'sdss_members' , false ) ) ||
			!( $roles_data = get_option( 'sdss_roles' , false ) ) ) {
				
				$msg = "No personnel data found.";
				$error_level='danger';
				
		} else {
			
			show_leadership( $roles_data , $leaders_data, $members_data );
			echo "<p class='modified'>Last modified: " . $option[0][ "leaders-modified" ] . "</p>";
		}
	}
	
} else {

	$msg = "An internal error has occurred. ";
	$error_level="danger";

}

if ( $msg ) echo "<div class='alert alert-$error_level'>" . $msg . "</div>";


function show_leadership( $roles , $leaders, $members, $parent_id = null ) {

	//find the ids of the roles with this parent id
	foreach( $roles as $this_role_id=>$this_role ) {
		if ( $parent_id == $this_role['parent_role_id'] ) {
			$the_parents[ $this_role_id ] = $this_role;
		}
	}

	//for each role with this parent id show all the roles that have this parent id
	if ( !empty( $the_parents ) ) {
		echo "<br><ul>\n";
		foreach( $the_parents as $this_parent_role_id=>$this_parent ) {
			echo "<li>" . $this_parent['role'] . ": " ;
			show_leaders( $leaders, $members , $this_parent_role_id  );
			show_leadership( $roles , $leaders, $members, $this_parent_role_id );
			echo "</li>\n";
		}
		echo "</ul>\n";
	}
}

function show_leaders( $leaders , $members , $role_id ) {
		$and = "";
		echo "<strong>";
		foreach( $leaders as $this_leader ) {
			if ( ( $this_leader['role_id'] == $role_id) && 
				( $this_leader['current'] ) &&
				( array_key_exists( $this_leader['member_id'] , $members ) ) ) {
					echo $and . $members[ $this_leader[ 'member_id' ] ][ 'fullname' ];
					if ( $members[ $this_leader[ 'member_id' ] ][ 'mc' ] ) echo "<sup>*</sup>";
					$and = ", ";
			}
		}
		echo "</strong>";
}
*/
?>