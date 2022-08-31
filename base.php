<?php 
get_template_part('templates/head'); 
?>
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

<?php if (is_front_page()): ?>
	<div class="wrap container-fluid" role="document">
<?php else: ?>
	<div class="wrap container" role="document">
<?php endif; ?>    

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
	<?php if (roots_display_sidebar()) : ?>
	<aside class="sidebar <?php echo roots_sidebar_class(); ?>" role="complementary">
		<?php 

		$sidebar_menu = new sdss_nav_menus();
		if ( $sidebar_menu->show( 'sidebar' ) || 
		
			//Show the Custom SDSS Nav Menu for this Page
			$sidebar_menu->show_cpt_menu( 'cpt' ) ) {
			echo "<div class='sdss-docs-sidebar'>";
			wp_nav_menu(array('theme_location' => $sidebar_menu->currentlocation, 'menu_class' => 'nav sdss-docs-sidenav', 'depth' => 0)); 
						
			echo "</div>";
		} 
		
		// show primary and custom post type sidebar widgets after main menu
		include roots_sidebar_path(); 

		?>
		</aside><!-- /.sidebar -->
	<?php endif; ?>
</div><!-- /.content -->
</div><!-- /.wrap -->
<?php get_template_part('templates/sitemap'); ?>
<?php get_template_part('templates/footer'); ?>

</body>
</html>
