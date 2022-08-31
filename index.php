<?php 
get_template_part('templates/page', 'header'); 
?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'roots'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php 
$thiscol=0;
while (have_posts()) {
	if ( !$thiscol ) echo "<div class='row'>" ;
		the_post(); 
		get_template_part('templates/content', get_post_format()); 
	if ( $thiscol ) echo "</div>";
	$thiscol = 1-$thiscol;
}
if ( $thiscol ) echo "</div>";

if ($wp_query->max_num_pages > 1) : ?>
<div class="row">
  <div class="col-xs-12">
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
  </div>
  </div>
<?php endif; ?>
