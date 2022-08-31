<div class="sdss-gallery">
<?php 
while (have_posts()) : 
	the_post(); 
	$current_page = get_post(); 
	$full_url = wp_get_attachment_url( $current_page->ID );
?>
<div class="row"><div class="col-xs-12"><div class="panel panel-primary"><div class="panel-heading">
<h1 class="panel-title"><?php 
echo "<a target='_blank' href='" . $full_url . "'>";
the_title(); 
echo "</a>";
?></h1></div>
<div class="panel-body">
<?php 
//the_content(); 
	echo "<div class='image well well-sm pull-left'>";
	echo "<a target='_blank' href='" . $full_url . "'>";
	echo wp_get_attachment_image( $current_page->ID , 'large' );
	echo "</a>";
	the_excerpt();
	echo "</div>\n";
	echo "<div class='description pull-left'>";
	the_content() ;
	echo '<div class="row">';
	/*/
	if ( $the_credit = get_post_meta( $current_page->ID, '_credit', true ) ) 
		echo '<div class="col-xs-12">Image Credit: ' . $the_credit . '<br>&nbsp;</div>';
	if ( $the_license = get_post_meta( $current_page->ID, '_license', true ) ) 
		echo '<div class="col-xs-12 acknowledgements">' . $the_license . '<br>&nbsp;</div>';
	/*/
	$options  = get_option( 'sdss_boilerplate' );
	$the_credit = get_post_meta( $current_page->ID, '_credit', true );
	$the_credit = ( empty( $the_credit ) ) ? $options[0]['image-default-credit'] : $the_credit ;
	$the_license = get_post_meta( $current_page->ID, '_license', true );
	$the_license = ( empty( $the_license ) ) ? $options[0]['image-default-license'] : $the_license ;	
	echo '<div class="col-xs-12">Image Credit: ' . $the_credit . '<br>&nbsp;</div>';
	echo '<div class="col-xs-12 ">License: ' . $the_license . '<br>&nbsp;</div>';
	echo '</div>';
	echo "</div>\n";	
?></div></div></div>
<div class="col-xs-12">
<ol class="breadcrumb">
  <li><a href="<?php echo home_url(); ?>">SDSS</a></li>
  <li><a href="/science/image-gallery/">Image Gallery</a></li>
  <li><?php echo $current_page -> post_title; ?></li>
</ol>
</div></div>
<?php endwhile; ?>
</div>
