<?php
/**
 * Roots includes
 */
require_once locate_template('/lib/utils.php');           // Utility functions
require_once locate_template('/lib/init.php');            // Initial theme setup and constants
require_once locate_template('/lib/wrapper.php');         // Theme wrapper class
require_once locate_template('/lib/sidebar.php');         // Sidebar class
require_once locate_template('/lib/config.php');          // Configuration
require_once locate_template('/lib/activation.php');      // Theme activation
require_once locate_template('/lib/titles.php');          // Page titles
require_once locate_template('/lib/cleanup.php');         // Cleanup
require_once locate_template('/lib/nav.php');             // Custom nav modifications
require_once locate_template('/lib/gallery.php');         // Custom [gallery] modifications
require_once locate_template('/lib/comments.php');        // Custom comments modifications
require_once locate_template('/lib/relative-urls.php');   // Root relative URLs
require_once locate_template('/lib/widgets.php');         // Sidebars and widgets
require_once locate_template('/lib/scripts.php');         // Scripts and stylesheets
require_once locate_template('/lib/sdss-cron.php');          // wp-cron functions
require_once locate_template('/lib/sdss-custom.php');          // Custom functions
require_once locate_template('/lib/sdss-nav-menus.php');          // Custom functions
require_once locate_template('/lib/sdss-shortcodes.php');          // Custom functions
require_once locate_template('/lib/sdss-parsejsons.php');          // Load SDSS Personnel JSONs
remove_filter('template_redirect','redirect_canonical');         // turn off auto-redirect of nonexistent pages to avoid ambiguity of page name