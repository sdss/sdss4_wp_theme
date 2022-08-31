<?php 
global $post;
$vac_title = ( ( empty( $post->vac_title ) ) ? 
				'' : 
				( ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') ) ?
					'<div class="vac-title">' . $post->vac_title . " (ID: " . $post->vac_id . ")" . '</div>' :
					'<div class="vac-title">' . $post->vac_title . '</div>' ) ); 

//$the_link = ( empty( $post->{'www_url'} ) ) ? get_permalink() : $post->{'www_url'} ;
$the_link = get_permalink();
?>
<article <?php post_class( 'col-xs-12 col-md-6' ); ?>>
<div class="vac-article">
  <header>
    <h2 class="entry-title"><a href="<?php echo $the_link; ?>"><?php echo $vac_title; ?></a></h2>
    <?php get_template_part('templates/entry-meta','vac'); ?>
  </header>
  <div class="clearfix"></div>
  <div class="entry-summary vac-content">
    <?php the_excerpt(); ?>
  </div>
</div>
</article>
