<?php 
while (have_posts()) : the_post(); 
	$current_page = get_post(); 
	if (locate_template('templates/content-' . $current_page -> post_name . '.php') != '') : 
			get_template_part('templates/content', $current_page -> post_name); 
	else: 
  		the_content(); 
	endif; 
  wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); 
endwhile; 
?>