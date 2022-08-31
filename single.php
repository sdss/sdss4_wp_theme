<?php 
$single = get_post();
if ( locate_template('templates/content-single-' . $single -> post_type . '.php') != '') : 
	get_template_part('templates/content', 'single-' . $single -> post_type ); 
else: 
	get_template_part('templates/content', 'single');
endif; 
?>
