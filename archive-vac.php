<?php
/*
Template Name: VAC Archive
*/
get_template_part('templates/page', 'header-vac'); 
// Show the VACs 
$option  = get_option( 'sync_json_options' , false );
$error_level="info";
$msg=false;

if ( $option !== false ) {

	if ( ( $option[0][ "vacs-updating" ] ) ) {
			
		$msg = "VACs are currently being updated. Please try again in a few minutes.";
		$error_level="warning";
		
	} else {
		
		if ( !have_posts(  ) ) {
			
			$msg = 'No VACs found.';
			$error_level="danger";
			
		} else {			

			echo '<div class="vac-container">';
			
			// Display the posts
			$i=0;
			while (have_posts()) : the_post();
			
				if ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') ) {
					get_template_part('templates/content', 'vac' ); 
				
				// Special Flag to indicate VAC is ready for primetime.
				} elseif ( $post->{'approve'} ) {
					get_template_part('templates/content', 'vac' ); 
				}

			endwhile; 
			echo '</div>';

			// Show Next / Previous links
			if ($wp_query->max_num_pages > 1) : 
				?><nav class="post-nav"><?php
				?><ul class="pager"><?php
				?><li class="previous"><?php next_posts_link(__('&larr; Older VACs', 'roots')); ?></li><?php
				?><li class="next"><?php previous_posts_link(__('Newer VACs &rarr;', 'roots')); ?></li><?php
				?></ul><?php
				?></nav><?php
			endif; 
		}
	}

} else {
	$msg = "An internal error has occurred. ";
	$error_level="danger";
}

if ( $msg ) echo "<div class='alert alert-$error_level'>" . $msg . "</div>";
?>
