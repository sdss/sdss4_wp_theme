<?php 
global $debug;
$debug = ( defined( 'WP_DEBUG' ) && ( WP_DEBUG ) );

$single_id = get_query_var( 'vac_id' , '' );

global $single_id;
global $this_dr_number;

global $current_dr;
$current_dr = ( defined( 'DATA_RELEASE' ) ) ? 
	( 'DR' . preg_replace("/[^0-9]/", "", DATA_RELEASE ) ) :
	'DR0' ;

if ($current_dr) {
	$this_dr_number = substr(DATA_RELEASE, -2);	
} else {
	$this_dr_number = substr(DATA_RELEASE, -2, -1)."5";
}

$vacs_data_json = @file_get_contents(  PATH_JSON . 'vacs.json' );
$vacs_data = json_decode( $vacs_data_json, true );

//echo "<h1><span style='color:red;'>".PATH_JSON."</span></h1>";

$all_vacs = array();
foreach ($vacs_data['vacs'] as $this_vac) {
	$all_vacs[$this_vac['id']] = $this_vac;
}
krsort($all_vacs);

if ( empty( $single_id ) ) {
	show_all_vacs($all_vacs);
	
	echo "<p class='modified'>VACs last updated: ".$vacs_data['modified']."</p>";
} else {
	show_single_vac($all_vacs);
}

function show_all_vacs($all_vacs) {

	echo "<h1 class='overview'>Value Added Catalogs</h1>";
	
	// Only show the content from the database on the archive page
	the_content();
	
	echo "<div class='vac-container'>";

	$i = 0;  // set up 2-column layout
	foreach ($all_vacs as $this_vac) {
		$vac_params_displayable = get_displayable_text($this_vac);
		
		$the_excerpt = $this_vac['description'];

/*		if ($i == 0) {
			echo "<div class='row'>";
		} */
				echo "<div class='col-xs-12 col-sm-6'>";
					echo "<article class='vac type-vac'>";
						echo "<div class='vac-article'>";
							echo "<header>";
								echo "<h2 class='entry-title'>";
									echo "<div class='vac-title'>";
										echo "<a href='".$vac_params_displayable['linktext']."'>";
											echo $vac_params_displayable['display_title'];
										echo "</a>";
									echo "</div>";
								echo "</h2>";
								echo "<div class='vac-tags'>";
									echo $vac_params_displayable['data_release_text'];
									echo $vac_params_displayable['survey_display'];
									echo $vac_params_displayable['show_cas_text'];
									echo $vac_params_displayable['object_type_display'];
								echo "</div>";
								echo "<div class='vac-authors'>";
									echo $vac_params_displayable['author_display'];
								echo "</div>";
							echo "</header>";
							//echo "<div class='clearfix'></div>";
							echo "<div class='entry-summary vac-content'>".$the_excerpt."</div>";
						echo "</div>";
					echo "</article>";
				echo "</div>";
/*		if ($i == 1) {
			echo "</div>";
		} */
		$i = 1 - $i;
	}
	echo "</div>";
	return;
}

function show_single_vac($all_vacs) {
	global $single_id;
	global $this_dr_number;
	$single_vac = array();
	
	foreach ($all_vacs as $this_vac) {
		$vac_id_comparison = strtolower(preg_replace('/\s/', '-', $this_vac['title']));
		if ($single_id == $vac_id_comparison) $single_vac = $this_vac;
	}

	if (count($single_vac) == 0) {
		echo "<h1 class='overview'>Value Added Catalog ".$single_id." not found</h1>";
		return;
	}

	$vac_params_displayable = get_displayable_text($single_vac);

	echo "<h1 class='overview'>".$vac_params_displayable['display_title']."</h1>";
	
	echo "<article class='vac type-vac'>";
		echo "<div class='vac-article'>";
			echo "<header>";
				echo "<div class='vac-tags'>";
					echo $vac_params_displayable['data_release_text'];
					echo $vac_params_displayable['survey_display'];
					echo $vac_params_displayable['cas_link_text'];
					echo $vac_params_displayable['object_type_display'];
				echo "</div>";
				echo "<div class='vac-authors'>";
					echo $vac_params_displayable['authors_all'];
				echo "</div>";
				echo "<div class='row'>";
					echo "<div class='col-xs-12 col-md-6'>";
						echo "<div class='vac-box vac-category'>";
							echo "<div class='vac-box-label'>Type: </div>";
							echo $vac_params_displayable['category_display'];
						echo "</div>";
					echo "</div>";
					echo "<div class='col-xs-12 col-md-6'>";
					echo "</div>";
					echo "<div class='clearfix'></div>";
					echo "<div class='col-xs-12'>";
						echo "<div class='vac-box vac-sas'>";
							echo "<div class='vac-box-label'>";
								echo "<span class='external'></span>";
								echo "Location on SAS: ";
							echo "</div>";
							echo $vac_params_displayable['sas_link'];
						echo "</div>";
					echo "</div>";
					echo "<div class='clearfix'></div>";
					echo "<div class='col-xs-12'>";
						echo "<div class='vac-box vac-datamodel'>";
							echo "<div class='vac-box-label'>";
								echo "<span class='external'></span>";
								echo "DATAMODEL: ";
							echo "</div>";
							echo $vac_params_displayable['datamodel_link'];
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</header>";
			echo "<div class='clearfix'></div>";
			echo "<div class='entry-summary vac-content'>";	
				echo $vac_params_displayable['www_url_display'];
				echo $vac_params_displayable['abstract_display'];
			echo "</div>";
			echo "<div class='clearfix'></div>";
		echo "</div>";
		

		// BREADCRUMBS
		echo "<div class='clearfix'></div>";
		echo "<div class='row'>";
			echo "<div class='col-xs-12'>";
				echo "<ol class='breadcrumb'>";
					echo "<li>";
						echo "<a href='/'>";
							echo "SDSS";
						echo "</a>";
					echo "</li>";
					echo "<li>";
						//echo "<a href='/dr".$this_dr_number."/data_access/value-added-catalogs/'>";
						echo "<a href='/dr"."17"."/data_access/value-added-catalogs/'>";
							echo "Value Added Catalogs";
						echo "</a>";
					echo "</li>";
					echo "<li>";
						echo $single_vac['title'];
					echo "</li>";
				echo "</ol>";
			echo "</div>";
		echo "</div>";
	echo "</article>";
}

function get_displayable_text($vac) {
	global $isTest;
	global $current_dr;
	global $this_dr_number;
	global $debug;

	if ($current_dr) {
		//$skyserver_root = "https://skyserver.sdss.org/dr16/";
		$skyserver_root = "https://preprod.skyserver.sdss.org/dr17/";
	} else {
		$skyserver_root = "https://skyserver.sdss.org/dr".$this_dr_number."/";
	}

	$data_site_root = "https://data.sdss.org";

	$vac_parsed['display_title'] = ( $isTest ) ? $vac['title'] . " (ID: " . $vac['id'] . ")" : $vac['title'];

	if ($vac['www_url'] == '') {
		$vac_parsed['www_url_display'] = '';
	} else {
		$thewwwlink = "/dr".($this_dr_number)."/".implode("/",array_slice(explode( "/", $vac['www_url']), 4))."/";

		if ($debug) {
			$whichsite = 'testng.sdss.org';
		} else {
			$whichsite = 'www.sdss.org';
		}
		$vac_parsed['www_url_display'] = "<div class='vac-link'>This VAC is described in full at ";
		$vac_parsed['www_url_display'] .= "<a href='".$thewwwlink."'>";
		$vac_parsed['www_url_display'] .= $whichsite;
		$vac_parsed['www_url_display'] .= $thewwwlink;
		$vac_parsed['www_url_display'] .= "</a>";
		$vac_parsed['www_url_display'] .= "</div>";
	}

	$linktext = get_permalink();
	$linktext .= "?vac_id=";
	$linktext .= strtolower(preg_replace("/\s/","-",$vac['title']));
	
	$vac_parsed['linktext'] = $linktext;

	$vac_parsed['show_cas_text'] = ($vac['includes_cas']) ? "<div class='vac-tag vac-cas'>CAS</div>" : "<div class='vac-tag vac-cas vac-no-cas'>No CAS</div>";
	$vac_parsed['cas_link_text'] = '';

	if ($vac['includes_cas']) {
	//if ($vac['cas_table'] <> "") {
		foreach ($vac['cas_table'] as $this_cas_table) {
/*			$vac_parsed['cas_link_text'] .= "<a href='".$skyserver_root."en/help/browser/browser.aspx#&&history=description+".$this_cas_table."+U' target='_blank'>";
				$vac_parsed['cas_link_text'] .= "<div class='vac-tag vac-table'>";
					$vac_parsed['cas_link_text'] .= "CAS: ";
					$vac_parsed['cas_link_text'] .= $this_cas_table;
				$vac_parsed['cas_link_text'] .= "</div>";
			$vac_parsed['cas_link_text'] .= "</a>"; */
			$vac_parsed['cas_link_text'] .= "<a href='http://skyserver.sdss.org/dr17/MoreTools/browser' target='_blank' class='vac-tag vac-table'>";
			$vac_parsed['cas_link_text'] .= "<div>";
			$vac_parsed['cas_link_text'] .= "<span style='font-weight:normal;color:#ffffff;'>";
			$vac_parsed['cas_link_text'] .= "CAS (click, then search in Schema Browser)";
			$vac_parsed['cas_link_text'] .= "</span>";
			$vac_parsed['cas_link_text'] .= "<br />";
			$vac_parsed['cas_link_text'] .= $this_cas_table;
			$vac_parsed['cas_link_text'] .= "</div>";
			$vac_parsed['cas_link_text'] .= "</a>";
		}
	}

	$vac_parsed['object_type_display'] = '';
	foreach ($vac['object_classes'] as $this_obj_type) {
		$vac_parsed['object_type_display'] .= "<div class='vac-tag vac-object'>".$this_obj_type."</div>";
	}

	$vac_parsed['survey_display'] = "<div class='vac-tag vac-survey'>".$vac['survey']."</div>";

	$vac_parsed['data_release_text'] = '';
	foreach ($vac['data_releases'] as $this_dr) {
		$vac_parsed['data_release_text'] .= ($this_dr == $current_dr) ? "<div class='vac-tag vac-dr vac-dr-latest'>".$this_dr."</div>" : "<div class='vac-tag vac-dr'>".$this_dr."</div>";
	}

	$the_authors = explode( ",", $vac['authors']);

	$vac_parsed['authors_all'] = $vac['authors'];

	switch ( count($the_authors) ) {
		case 0:
			$vac_parsed['author_display'] = "";
			break;
		case 1:
			$vac_parsed['author_display'] = "<div class='vac-authors'>" . $the_authors[0] . "</div>";
			break;
		case 2: 
			$vac_parsed['author_display'] = "<div class='vac-authors'>" . $the_authors[0] .  ", " . $the_authors[1] . "</div>";
			break;
		default:
			$vac_parsed['author_display'] = "<div class='vac-authors'>" . $the_authors[0] . " et&nbsp;al." . "</div>";
	}

	$vac_parsed['category_display'] = $vac['category'];

	$vac_parsed['sas_link'] = "";
	$vac_parsed['sas_link'] .= "<a href='".$data_site_root.$vac['sas_folder']."' target='_blank'>";
			$vac_parsed['sas_link'] .= $data_site_root.$vac['sas_folder'];
	$vac_parsed['sas_link'] .= "</a>";

	$vac_parsed['datamodel_link'] = "";
	$vac_parsed['datamodel_link'] .= "<a href='".$vac['datamodel_url']."' target='_blank'>";
			$vac_parsed['datamodel_link'] .= $vac['datamodel_url'];
	$vac_parsed['datamodel_link'] .= "</a>";

	//$vac_parsed['abstract_display'] .= '<p>'

	$vac_parsed['abstract_display'] = "";
	$vac_parsed['abstract_display'] .= "<p>";
	$vac_parsed['abstract_display'] .= preg_replace('/\r\n/','</p><p>',$vac['abstract']);
	//$vac_parsed['abstract_display'] .= preg_replace("/\`\`/",'"',$vac['abstract']);
	//$vac_parsed['abstract_display'] .= preg_replace("/\'\'/'",'"',$vac['abstract']);
	$vac_parsed['abstract_display'] .= "</p>";

	
	return $vac_parsed;
}
/*
//category
modified
//object_classes
//cas_table
description
//title
datamodel_url
approve
abstract
includes_marvin
publication_ids
//includes_cas
//data_releases
//survey
//authors
www_url
cas_join
identifier
sas_folder
//id
*/




?>