<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>
<?php 
	if (locate_template('templates/pre-header.php') != '') {
		get_template_part('templates/pre-header');
	}
		
    do_action('get_header');
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
		get_template_part('templates/header-top-navbar');
    } else {
		get_template_part('templates/header');
    }
  ?>

  <div class="wrap container" role="document">
    <div class="content row">
<?php 
//show secondary nav menu
$secondtier_menu = new sdss_nav_menus();

if ( $secondtier_menu->show( 'secondtier' ) ) {

	wp_nav_menu(array('theme_location' => $secondtier_menu->currentlocation, 'menu_class' => 'nav nav-pills nav-justified')); 

}

?>
      <main class="main <?php echo roots_main_class(); ?>" role="main">    
        <?php include roots_template_path(); ?>
      </main><!-- /.main -->
    </div><!-- /.content -->
  </div><!-- /.wrap -->
  <?php get_template_part('templates/sitemap'); ?>
  <?php get_template_part('templates/footer'); ?>
</body>
</html>
