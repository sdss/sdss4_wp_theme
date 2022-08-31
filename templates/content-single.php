<?php while (have_posts()) : the_post(); ?>
<?php $current_page = get_post(); ?>
<article <?php post_class(); ?>>
<header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
</header>
<div class="entry-content">
<div class="row"><div class="col-xs-12">
<?php 
if ( function_exists( 'get_cfc_meta' ) ) 
	$this_cfc_meta = get_cfc_meta( $current_page -> post_type.'-meta' );
if (count($this_cfc_meta)) {
	?><div class="row"><div class="col-xs-12 col-md-6"><?php 
	the_content(); 
	?></div><div class="col-xs-12 col-md-6"><div class="well"><?php 
	foreach ($this_cfc_meta[0] as $this_key => $this_value ){
		if ( strpos ( $this_key ,  "!" ) === 0 ) continue;
		echo "<strong>" . ucfirst( str_replace ( '-' , ' ' , $this_key ) ) . ": </strong>";
		echo $this_value . "<br>\n";
	}
	?></div></div></div><?php
} else {
	the_content(); 
}
$this_type = ( strcmp( "post" , $current_page -> post_type ) === 0 ) ? "Press Releases" : $current_page -> post_type ;
$this_slug = sanitize_title( $this_type, "post" );
?></div></div>
<div class="row">
<div class="col-xs-12">
<ol class="breadcrumb">
  <li><a href="/">SDSS</a></li>
  <li><a href="<?php echo '/' . $this_slug ; ?>"><?php echo $this_type; ?></a></li>
  <li><?php echo $current_page -> post_title; ?></li>
</ol>
</div></div></div>

<footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
</footer>
</article>
<?php endwhile; ?>
