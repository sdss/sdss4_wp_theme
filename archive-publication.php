<?php
/*
Archive Name: Publication Archive
*/
get_template_part('templates/page', 'header-publication'); 

// Display Warning
if (!have_posts()) : 
	?><div class="alert alert-warning"><?php 
	_e('Sorry, no publications found.', 'roots'); 
	get_search_form();
	?></div><?php 
endif; 

echo '<div class="publication-container">';
echo get_pub_list(  );
echo '</div>';
?>
