<?php
/**
 * Roots initial setup and constants
 */
function roots_setup() {
  // Make theme available for translation
  load_theme_textdomain('roots', get_template_directory() . '/lang');

  // Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
  register_nav_menus(array(
    'primary_navigation' => __('Primary Navigation', 'roots'),
    'secondtier_first' => __('Secondary Tier First', 'roots'),
    'secondtier_second' => __('Secondary Tier Second', 'roots'),
    'secondtier_third' => __('Secondary Tier Third', 'roots'),
    'secondtier_fourth' => __('Secondary Tier Fourth', 'roots'),
    'secondtier_fifth' => __('Secondary Tier Fifth', 'roots'),
    'secondtier_sixth' => __('Secondary Tier Sixth', 'roots'),
    'secondtier_seventh' => __('Secondary Tier Seventh', 'roots'),
    'secondtier_eighth' => __('Secondary Tier Eighth', 'roots'),
    'sidebarmenu_first' => __('Sidebar Menu First', 'roots'),
    'sidebarmenu_second' => __('Sidebar Menu Second', 'roots'),
    'sidebarmenu_third' => __('Sidebar Menu Third', 'roots'),
    'sidebarmenu_fourth' => __('Sidebar Menu Fourth', 'roots'),
    'sidebarmenu_fifth' => __('Sidebar Menu Fifth', 'roots'),
    'sidebarmenu_sixth' => __('Sidebar Menu Sixth', 'roots'),
    'sidebarmenu_seventh' => __('Sidebar Menu Seventh', 'roots'),
    'sidebarmenu_eighth' => __('Sidebar Menu Eighth', 'roots'),
    'sidebarmenu_ninth' => __('Sidebar Menu Ninth', 'roots'),
    'sidebarmenu_tenth' => __('Sidebar Menu Tenth', 'roots'),
    'sidebarmenu_eleventh' => __('Sidebar Menu Eleventh', 'roots'),
    'sidebarmenu_twelfth' => __('Sidebar Menu Twelfth', 'roots'),
    'sidebarmenu_thirteenth' => __('Sidebar Menu Thirteenth', 'roots'),
    'sidebarmenu_fourteenth' => __('Sidebar Menu Fourteenth', 'roots'),
    'sidebarmenu_fifteenth' => __('Sidebar Menu Fifteenth', 'roots'),
    'cpt_vac' => __('Value Added Catalogs Menu', 'roots'),
    //'cpt_releases' => __('CPT Press Releases Menu', 'roots'),
  ));

  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  // set_post_thumbnail_size(150, 150, false);
  // add_image_size('category-thumb', 300, 9999); // 300px wide (and unlimited height)

  // Add post formats (http://codex.wordpress.org/Post_Formats)
  // add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('/assets/css/editor-style.css');
}
add_action('after_setup_theme', 'roots_setup');
