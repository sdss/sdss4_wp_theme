<?php
/*
 * This file contains functions that import the json files for SDSS Personnel, Affiliations, Coco, Leadership, 
 * Management Committee (MC), Members, Roles, and Publications into the WP database.
 * 
 */

// This function runs whenever the Backend of the WordPress site is loaded.
// Implementing with wp-cron instead of admin page load. 
// add_action( 'init', 'sdss_process_jsons' );

function sdss_process_jsons() {
	$debug = false;
	$msg = "WP Cron on test.sdss.org on " . gethostname();
	
	//if ($debug) echo "Starting...<br>\n";
	$msg .= "Starting...<br>\n";
	if ( false === ( $option  = get_option( 'sync_json_options' ) ) ) {
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
	}
	//$webrootpath = $option[0]['path'];
	$uploadpath = str_replace ( '/var/www/' , $option[0]['path'] , WP_CONTENT_DIR ) . '/uploads/jsons/';
	//if ($debug) echo "uploadpath: $uploadpath<br>\n";
	$msg .=  "uploadpath: $uploadpath<br>\n";
	
	if ( 0 === strcmp( 'yes' , $option[0]['sync-json'] ) ) {
		
		//the json files to read
		$file_prefixes = array( 
			'affiliations', 
			'architects', 
			'project', 
			'coco', 
			'leaders', 
			'mc', 
			'members', 
			'publications',
			'roles',
			'vacs',
		);
		
		//if ($debug) echo "&nbsp;<br>\n" . "Reading " . "<br>\n";
		$msg .= "&nbsp;<br>\n" . "Reading " . "<br>\n";
		foreach( $file_prefixes as $this_prefix ) {
		
			// read data if file exists
			$$this_prefix = @file_get_contents( $uploadpath . $this_prefix . ".json" );
			if ( !(false === ( $$this_prefix ) ) ) {

				//if ($debug) echo "Found $this_prefix.<br>\n";
				$msg .= "Found $this_prefix.<br>\n";
				
				$$this_prefix = json_decode( $$this_prefix );
				
				if ( $$this_prefix->modified ===  $option[0][$this_prefix  . "-modified"] ) {
					${$this_prefix.'_modified'} = false;
					//if ($debug) echo "Not updating $this_prefix." . "<br>\n";
					$msg .= "Not updating $this_prefix." . "<br>\n";
				} else {
					${$this_prefix.'_modified'} = true;
					//if ($debug) echo "Maybe updating $this_prefix." . "<br>\n";
					$msg .=  "Maybe updating $this_prefix." . "<br>\n";
				}
				${$this_prefix.'_updating'} = ( $option[0][$this_prefix  . "-updating"] == "true" ) 
					? true : false ; 
					
			}
		}
	}

	//if ($debug) echo "&nbsp;<br>\n" . "Processing " . "<br>\n";
	$msg .= "&nbsp;<br>\n" . "Processing " . "<br>\n";
	
		// SDSS_PARTICIPATIONS
		// SDSS_PROJECT
	if ( !$project_updating && $project_modified ) {
		
		//if ($debug) echo "Updating project " . "<br>\n";
		$msg .= "Updating project " . "<br>\n";

		$option[0]['project-updating'] = "true";
		update_option( 'sync_json_options' , $option );
		
		foreach( $project->participations as $this_participation ) {
			$participations_data[ $this_participation->participation_id ] = array( 
				'title'=>$this_participation->title, 
			);
			foreach( $this_participation->affiliations as $this_affiliation ) {
				$project_data[ $this_affiliation->affiliation_id ] = array(
					'title' => $this_affiliation->title ,
					'type' => '', 
					'participation_id' => $this_participation->participation_id, 
				);
			}
		}

		foreach( $project->associate_members as $this_affiliation ) {
			$project_data[ $this_affiliation->affiliation_id ] = array(
				'title' => $this_affiliation->title ,
				'type' => 'associate_member', 
				'participation_id' => '', 
			);
		}
		
		foreach( $project->full_members as $this_affiliation ) {
			$project_data[ $this_affiliation->affiliation_id ] = array(
				'title' => $this_affiliation->title ,
				'type' => 'full_member', 
				'participation_id' => '', 
			);
		}
		
		update_option( 'sdss_participations' , $participations_data );
		update_option( 'sdss_project' , $project_data );
		
		$option[0]['project-updating'] = "";
		$option[0]['project-modified'] = $project->modified;
		update_option( 'sync_json_options' , $option );

		//if ($debug) echo "Done updating project " . "<br>\n";
		$msg .= "Done updating project " . "<br>\n";

	} elseif ( $project_updating ) {
	
		//if ($debug) echo "Warning: project-updating is true. Stopping." . "<br>\n";
		$msg.= "Warning: project-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
		
	} else {
		
		//if ($debug) echo "Not updating project or participations." . "<br>\n";
		$msg .= "Not updating project or participations." . "<br>\n";
		
	}
	
	// SDSS_MEMBERS
	if ( !$members_updating && !$mc_updating ) {
		
		$option[0]['members-updating'] = "true";
		$option[0]['mc-updating'] = "true";
		update_option( 'sync_json_options' , $option );

		// Member modified, MC modified or not
		if ( $members_modified ) {	
	
			//if ($debug) echo "Updating members and mc." . "<br>\n";
			$msg.=  "Updating members and mc." . "<br>\n";

			// get members data
			foreach( $members->members as $this_member ) {
				$members_data[ $this_member->member_id ] = array(
					'affiliation_id' => $this_member->affiliation_id ,
					'fullname' => $this_member->fullname , 
					'email' => $this_member->email , 
					'mc' => false ,
				);
			}

			// use mc whether mc modified or not
			if ( !empty( $mc ) ) {
				foreach( $mc->mc as $this_mc ) {
					if ( array_key_exists ( $this_mc->member_id , $members_data ) ) $members_data[ $this_mc->member_id ]['mc'] = true;
				}
				
			// but if we can't find the mc json, get the mc data from the old members data
			} else {
				$old_members = get_option( 'sdss_members' );
				foreach ( $old_members as $this_old_key=>$this_old_member ) {
					
					if ( array_key_exists ( $this_old_key , $members_data ) ) $members_data[ $this_old_key ]['mc'] = true;
					
				}
			
			}
			
		// Members not modified, MC modified
		} elseif ( $mc_modified ) {
			
			//if ($debug) echo "Updating mc." . "<br>\n";
			$msg.=  "Updating mc." . "<br>\n";
			
			// get current member and set mc to false for all
			$members_data = get_option( 'sdss_members' );
			array_walk( $members_data , create_function( '&$v' , '$v["mc"] = false;' ) );
			
			// use new mc data to set mc field
			foreach( $mc->mc as $this_mc ) {
				if ( array_key_exists ( $this_mc->member_id , $members_data ) ) $members_data[ $this_mc->member_id ]['mc'] = true;
			}

			update_option( 'sdss_members' , $members_data );
		
		// Members not modified, MC not modified
		} else {
			
			//if ($debug) echo "Not updating members." . "<br>\n";
			$msg.=  "Not updating members." . "<br>\n";

		}
		
		$option[0]['members-updating'] = "";
		$option[0]['mc-updating'] = "";
		$option[0]['members-modified'] = $members->modified;
		$option[0]['mc-modified'] = $mc->modified;
		update_option( 'sync_json_options' , $option );
			
	} else {
		
		//if ($debug) echo "Warning: members-updating or mc-updating is true. Stopping." . "<br>\n";
		$msg.=  "Warning: members-updating or mc-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
	}
	
	// SDSS_AFFILIATION
	if ( !$affiliations_updating && $affiliations_modified ) {
		
		//if ($debug) echo "Updating affiliations " . "<br>\n";
		$msg.=  "Updating affiliations " . "<br>\n";

		$option[0]['affiliations-updating'] = "true";
		update_option( 'sync_json_options' , $option );
		
		foreach( $affiliations->affiliations as $this_affiliation ) {
			$affiliation_data[ $this_affiliation->affiliation_id ] = array(
					'title'=>$this_affiliation->title,
					'fulladdress'=>$this_affiliation->fulladdress,
					'address'=>$this_affiliation->address,
					'department'=>$this_affiliation->department,
					'identifier'=>$this_affiliation->identifier,
			);
		}
		update_option( 'sdss_affiliation' , $affiliation_data );
		
		$option[0]['affiliations-updating'] = "";
		$option[0]['affiliations-modified'] = $affiliations->modified;
		update_option( 'sync_json_options' , $option );
		
	} elseif ( $affiliations_updating ){
		
		//if ($debug) echo "Warning: affiliations-updating is true. Stopping." . "<br>\n";
		$msg.=  "Warning: affiliations-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
		
	} else {
		
			//if ($debug) echo "Not updating affiliations." . "<br>\n";
			$msg.=  "Not updating affiliations." . "<br>\n";

	}

	// SDSS_COCO
	if ( !$coco_updating && $coco_modified ) {
		
		//if ($debug) echo "Updating coco " . "<br>\n";
		$msg.=  "Updating coco " . "<br>\n";

		$option[0]['coco-updating'] = "true";
		update_option( 'sync_json_options' , $option );
		
		$coco_data['spokesperson']['member_id'] = $coco->spokesperson->member_id;
		$coco_data['spokesperson']['affiliation_id'] = @$coco->spokesperson->affiliation_id;
		$coco_data['spokesperson']['participation_id'] = @$coco->spokesperson->participation_id;
		
		$coco_data['lessthan3']['member_id'] = $coco->lessthan3->member_id;
		$coco_data['lessthan3']['affiliation_id'] = @$coco->lessthan3->affiliation_id;
		$coco_data['lessthan3']['participation_id'] = @$coco->lessthan3->participation_id;

		foreach( $coco->coco as $this_coco ) {
			$coco_data[ $this_coco->member_id ] = array(
					'affiliation_id'=>$this_coco->affiliation_id,
					'participation_id'=>$this_coco->participation_id,
			);
		}
		update_option( 'sdss_coco' , $coco_data );
		
		$option[0]['coco-updating'] = "";
		$option[0]['coco-modified'] = $coco->modified;
		update_option( 'sync_json_options' , $option );
		
	} elseif ( $coco_updating ) {
		
		//if ($debug) echo "Warning: coco-updating is true. Stopping." . "<br>\n";
		$msg.=  "Warning: coco-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
		
	} else {
		
		//if ($debug) echo "Not updating coco." . "<br>\n";
		$msg.=  "Not updating coco." . "<br>\n";

	}

	// SDSS ARCHITECTS
	if ( !$architects_updating && $architects_modified ) {
		
		//if ($debug) echo "Updating architects " . "<br>\n";
		$msg.=  "Updating architects " . "<br>\n";

		$option[0]['architects-updating'] = "true";
		update_option( 'sync_json_options' , $option );

		foreach( $architects->architects as $this_architect ) {
			$architects_data[ $this_architect->member_id ] = array(
				'comment' => $this_architect->comment ,
			);
		}
		update_option( 'sdss_architects' , $architects_data );
		
		$option[0]['architects-updating'] = "";
		$option[0]['architects-modified'] = $architects->modified;
		update_option( 'sync_json_options' , $option );
		
	} elseif ( $architects_updating ) {
		
		//if ($debug) echo "Warning: architects-updating is true. Stopping." . "<br>\n";
		$msg.=  "Warning: architects-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
		
	} else {
		
		//if ($debug) echo "Not updating architects." . "<br>\n";
		$msg.=  "Not updating architects." . "<br>\n";

	}
	
	// SDSS_ROLES
	if ( !$roles_updating && $roles_modified ) {

		//if ($debug) echo "Updating roles " . "<br>\n";
		$msg.=  "Updating roles " . "<br>\n";

		$option[0]['roles-updating'] = "true";
		update_option( 'sync_json_options' , $option );
		
		foreach( $roles->roles as $this_role ) {
			$roles_data[ $this_role->role_id ] = array(
				'parent_role_id' => $this_role->parent_role_id ,
				'role' => $this_role->role ,
			);
		}
		update_option( 'sdss_roles' , $roles_data );
		
		$option[0]['roles-updating'] = "";
		$option[0]['roles-modified'] = $roles->modified;
		update_option( 'sync_json_options' , $option );
		
	} elseif ( $roles_updating ) {
		
		//if ($debug) echo "Warning: roles-updating is true. Stopping." . "<br>\n";
		$msg.=  "Warning: roles-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
		
	} else {
		
			//if ($debug) echo "Not updating roles." . "<br>\n";
			$msg.=  "Not updating roles." . "<br>\n";

	}

	// SDSS_LEADERS
	if ( !$leaders_updating && $leaders_modified ) {

		//if ($debug) echo "Updating leaders " . "<br>\n";
		$msg.=  "Updating leaders " . "<br>\n";

		$option[0]['leaders-updating'] = "true";
		update_option( 'sync_json_options' , $option );
		
		foreach( $leaders->leaders as $this_leader ) {
			$leaders_data[  ] = array(
				'role_id' => $this_leader->role_id ,
				'member_id' => $this_leader->member_id ,
				'current' => $this_leader->current ,
				'chair' => $this_leader->chair ,
			);
		}
		update_option( 'sdss_leaders' , $leaders_data );
		
		$option[0]['leaders-updating'] = "";
		$option[0]['leaders-modified'] = $leaders->modified;
		update_option( 'sync_json_options' , $option );
		
	} elseif ( $leaders_updating ) {
		
		//if ($debug) echo "Warning: leaders-updating is true. Stopping." . "<br>\n";
		$msg.=  "Warning: leaders-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
		
	} else {
		
			//if ($debug) echo "Not updating leaders." . "<br>\n";
			$msg.= "Not updating leaders." . "<br>\n";

	}

	// SDSS_PUBLICATIONS
	if ( !$publications_updating && $publications_modified ) {

		//if ($debug) echo "Updating publications " . "<br>\n";
		$msg.= "Updating publications " . "<br>\n";

		$option[0]['publications-updating'] = "true";
		update_option( 'sync_json_options' , $option );
		
		foreach( $publications->publications as $this_publication ) {
			$publications_data[$this_publication->publication_id] = array(
				'authors' => $this_publication->authors ,
				'journal_volume' => $this_publication->journal_volume,
				'doi_url' => $this_publication->doi_url,
				'doi' => $this_publication->doi,
				'arxiv_url' => $this_publication->arxiv_url,
				'arxiv' => $this_publication->arxiv,
				'adsabs_url' => $this_publication->adsabs_url,
				'adsabs' => $this_publication->adsabs,
				'title' => $this_publication->title,
				'status' => $this_publication->status,
				'journal' => $this_publication->journal,
				'journal_reference' => $this_publication->journal_reference,
				'journal_year' => $this_publication->journal_year,
				'category' => $this_publication->category,
				'survey' => $this_publication->survey,
				'identifier' => $this_publication->identifier,
			);
		}
		update_option( 'sdss_publications' , $publications_data );
		
		//The New Way - as CPT
		$option[0]['publications-updating'] = "";
		$option[0]['publications-modified'] = $publications->modified;
		update_option( 'sync_json_options' , $option );
		
	} elseif ( $publications_updating ) {
		
		//if ($debug) echo "Warning: publications-updating is true. Stopping." . "<br>\n";
		$msg.= "Warning: publications-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
		
	} else {
		
		//if ($debug) echo "Not updating publications." . "<br>\n";
		$msg.= "Not updating publications." . "<br>\n";

	}

	// SDSS_VACS
	if ( !$vacs_updating && $vacs_modified ) {

		//if ($debug) echo "Updating vacs " . "<br>\n";
		$msg.= "Updating vacs " . "<br>\n";

		$option[0]['vacs-updating'] = "true";
		update_option( 'sync_json_options' , $option );
		
		//The Old Way - as an Option
		foreach( $vacs->vacs as $this_vac_key=>$this_vac ) {
			$this_vac->abstract = wpautop( $this_vac->abstract );
			$vacs_data[ $this_vac->id ] = $this_vac;
		}
		krsort( $vacs_data );
		foreach( $vacs_data as $k=>$v ) {
			$vacs_data[ sanitize_title( $v->title ) ] = $v;
			unset( $vacs_data[ $k ] );
		}
		update_option( 'sdss_vacs' , $vacs_data );
		
		$option[0]['vacs-updating'] = "";
		$option[0]['vacs-modified'] = $vacs->modified;
		update_option( 'sync_json_options' , $option );
		
	} elseif ( $vacs_updating ) {
		
		//if ($debug) echo "Warning: vacs-updating is true. Stopping." . "<br>\n";
		$msg.= "Warning: vacs-updating is true. Stopping." . "<br>\n";
		if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
		return;
		
	} else {

		//if ($debug) echo "Not updating vacs" . "<br>\n";
		$msg.= "Not updating vacs" . "<br>\n";
		
	}
	if ($debug) wp_mail( 'bsouter@jhu.edu', 'WordPress Cron', $msg );
}