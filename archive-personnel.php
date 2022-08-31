<?php get_template_part('templates/page', 'header'); ?>

<p>SDSS People</p>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('No people found.', 'roots'); ?>
  </div>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', get_post_format()); ?>
  <?php if(($wp_query->current_post + 1) % 2 == 0) : ?>
    <div class="clearfix hidden-xs"></div>
  <?php endif; ?>
<?php endwhile; ?>


<?php if ($wp_query->max_num_pages > 1) : ?>
  <div class="clearfix"></div>
  <div class="row">
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
  </div>
<?php endif; ?>

