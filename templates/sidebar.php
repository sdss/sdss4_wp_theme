<?php global $post; ?>
<?php dynamic_sidebar('sidebar-primary'); ?>
<?php dynamic_sidebar('sidebar-' . get_post_type(  ) ); ?>
<?php dynamic_sidebar('sidebar-' . $post->post_name ); ?>