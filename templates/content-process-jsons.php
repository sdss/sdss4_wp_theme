<?php 
echo "Processing jsons";
echo "<br>\n";
sdss_process_jsons();
while (have_posts()) : the_post(); 
	the_content(); 
endwhile; 
?>
