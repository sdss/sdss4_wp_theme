<?php 
/**
 * Perform actions for sdss theme.
 */
add_action( 'admin_init', 'sdss_menu_actions' );

/**
 * Add notices 
**/ 
function sdss_menu_actions() {

    add_action( 'admin_notices', 'sdss_menu_message' );
	return;
}

/**
 * Add notice to menu page
**/ 
function sdss_menu_message() {
	if( current_user_can( 'manage_options' ) ) {
		$screen = get_current_screen();
		if ( strpos ( $screen->id , "nav-menus" ) === 0 ) {
			echo "<div class='updated'>";
			echo "<dl><dt>SDSS Menu Names:</dt>\n";
			echo "<dd>";
			echo "Menu names should begin and end with a slash, e.g. /dr13/, /dr13/algorithms/, /surveys/.<br>\n";
			echo "Menu names should be assigned according to the Parent Page you want them to show up on. For example, the menu /dr13/ will show up on all pages that start with /dr13/ including /dr13/, /dr13/data_access/, /dr13/spectro/, etc.<br>\n";
			echo "/dr13/spectro/ will show up on all pages below /dr13/spectro/, such as /dr13/spectro/overview/, etc.\n";
			echo "</dd>\n";
			echo "<dl><dt>SDSS Menu Locations:</dt>\n";
			echo "<dd>";
			echo "Menu locations that start with Secondary Tier will display below the primary navigation. Menu locations that start with Sidebar will display on the right hand sidebar. The order (first, second, etc) is not important.\n";
			echo "</dd>\n";
			echo "<dl><dt>SDSS Menu Styles:</dt>\n";
			echo "<dd>";
			echo "To indent a sidebar menu item, assign it the CSS style 'indent'.\n";
			echo "</dd>\n";
			echo "</div>\n";
		}
	}
}
 
 /**
 * Show menus for sdss theme in the right place at the right time.
 */
 class sdss_nav_menus
{
	private $menus_used = array();
	private $themelocations;
	private $menulocations;	

	public $currentlocation;
	
	/**
	 * Get the theme locations and the menus that are assigned there.
	 */
	function __construct( ) {
	
   		$this->themelocations = get_registered_nav_menus();
		$this->menulocations = get_nav_menu_locations();

		$this->get_menus_used();
}
	
	/**
	 * Show the assigned menu at this location, if the menu name is in the page's hierarchy.
	 */
	public function show( $thislocation ) {
		//see if there are any menus to show in this location.
		
		//the menu's slug will start with $thislocation if it's used here
		foreach ($this->menus_used as $this_menu ) {
			if ( strpos( $this_menu[0] , $thislocation ) === 0 ) {
				//if ( ( defined( 'DATA_RELEASE' ) && strcmp( $thislocation , 'secondtier' ) === 0 ) || ( strpos( $this->get_permalink() , $this_menu[2] ) === 0 ) )  {
				if ( strpos( $this->get_permalink() , $this_menu[2] ) === 0 ) {
					$this->currentlocation  = $this_menu[0];
					return true;
				}
			}
		}
		
		$this->currentlocation  = '';
		return false;
	}

	/**
	 * Show the assigned menu at for this cpt, if the menu name is the cpt.
	 */
	public function show_cpt_menu( $thiscpt ) {
		
		//see if there are any menus to show in this location.
		foreach ($this->menus_used as $this_menu ) {
			//the menu's slug will start with $thiscpt if it's used here
			if ( strpos( $this_menu[0] , $thiscpt . '_' . get_post_type(  ) ) === 0 ) {
					$this->currentlocation  = $this_menu[0];
					return true;
			}
		}
		
		$this->currentlocation  = '';
		return false;
	}
	
	function get_menus_used(  )
    {

		//find all the menu locations that have been assigned menu objects
		foreach ( $this->themelocations as $thisslug => $thislocation ) {
			if (!empty($this->menulocations[$thisslug])) {
				$menuid = $this->menulocations[$thisslug];
				if ($menuid) {
				
					$menuobj = wp_get_nav_menu_object($menuid);
					$this->menus_used[] = array($thisslug, $menuid,  $menuobj->name ) ;
					
				}
			}
		}
		
		return;
	}
		
	//get this page's permalink
	function get_permalink(   )
    {
		$thispage= get_post(  );
		if (empty($thispage->post_name)) return '';
		$thispermalink = '/' . $thispage->post_name . '/';
		foreach( get_ancestors( $thispage->ID , 'page' ) as $thisancestorid ) {
			$thisancestor = get_post( $thisancestorid );
			$thispermalink = '/' . $thisancestor->post_name . $thispermalink;		
		}
		
		return $thispermalink;
	}
}	

//Add menu and nav shortcode support
//add_action( 'after_setup_theme', 'sdss_nav_shortcode_setup' );

