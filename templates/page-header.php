<?php
global $post;
if ( !empty( $post ) 
	&& !empty( $post -> post_name ) 
&& ( locate_template( 'templates/page-header-' . $post -> post_name . '.php' ) != '' ) ) : 
	get_template_part( 'templates/page', 'header-' . $post -> post_name);
else: 
?>
  <h1 id="overview">
    <?php echo roots_title(); ?>
  </h1>
<?php 
	if (is_page( array (386, 'help') )) :
		get_template_part('templates/searchform', 'page'); 
	endif;
endif; 
?>
