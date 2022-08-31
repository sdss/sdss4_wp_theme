<?php while (have_posts()) : the_post(); ?>
<?php $current_page = get_post(); ?>
<article <?php post_class(); ?>>
<header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
</header>
<div class="entry-content">
<div class="row">
<div class="col-xs-12">.<?php the_content(); ?></div>
</div>
<div class="row">
<div class="col-xs-12">
<ol class="breadcrumb">
  <li><a href="<?php echo home_url(); ?>">SDSS</a></li>
  <li><a href="/science/image-gallery/">Image Gallery</a></li>
  <li><?php echo $current_page -> post_title; ?></li>
</ol>
</div></div></div>

<footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
</footer>
</article>
<?php endwhile; ?>
