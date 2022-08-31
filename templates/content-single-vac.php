<?php 
$option  = get_option( 'sync_json_options' , false );
$error_level="info";
$msg=false;

if ( $option !== false ) {

	if ( ( $option[0][ "vacs-updating" ] ) ) {
			
		$msg = "VACs are currently being updated. Please try again in a few minutes.";
		$error_level="warning";
		
	} else {
		$sas_url = "https://data.sdss.org";
		while (have_posts()) : the_post(); 
			$post = get_post(); 
			
			$vac_title = ( ( empty( $post->vac_title ) ) ? 
				'' : 
				( ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') ) ?
					'<div class="vac-title">' . $post->vac_title . " (ID: " . $post->vac_id . ")" . '</div>' :
					'<div class="vac-title">' . $post->vac_title . '</div>' ) ); 
					
			$post_modified = ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') ) ?
				'<div class="vac-modified">VAC Last Updated: ' . $post->{'vac_modified'} . '</div>' :
				'';

			$www_url = empty( $post->{'www_url'} ) ?
				'' :
				'<div class="vac-link">This VAC is described in full on <a class="vac-link-www" target="_blank" href="' . $post->{'www_url'} . '">this web page</a>.</div>';
			$the_pubs = empty( $post->{'publication_ids'} ) ? array() : $post->{'publication_ids'} ;
			array_walk( $the_pubs , 
				function( &$value , &$key ) {
					$value = sprintf("SDSS-IV Paper %'.04d", $value);
				});

			// Category, SAS
			$the_category = ( empty( $post->{'vac_category'} ) ) ? 
				"" : 
				'<div class="vac-box vac-category">' . 
				'<div class="vac-box-label">Type: </div>' . 
					$post->{'vac_category'} . 
					'</div>';
			$announcement = ( ( defined( 'WP_ENV' ) && ( WP_ENV === 'development') ) && !empty( $post->{'vac_id'} ) ) ? 
				'<div class="vac-box vac-announce">' . 
				'<div class="vac-box-label"><span class="external"></span>Collaboration Announcement: </div>' . 
				'<a href="https://internal.sdss.org/collaboration/vacs/browse/select/id=' . $post->vac_id . '" target="_blank">View Announcement</a>' . 
				'</div>' :
				"" ;
			$the_sas = ( empty( $post->{'sas_folder'} ) ) ? 
				"" : 
				'<div class="vac-box vac-sas">' . 
				'<div class="vac-box-label"><span class="external"></span>Location on SAS: </div>' . 
					'<a href="' . $sas_url . $post->{'sas_folder'} . '" target="_blank">' . $sas_url . $post->{'sas_folder'} . '</a>' . 
					'</div>';
			$datamodel = ( empty( $post->{'datamodel'} ) ) ? 
				"" : 
				'<div class="vac-box vac-datamodel">' . 
				'<div class="vac-box-label"><span class="external"></span>DATAMODEL: </div>' . 
				'<a href="' . $post->{'datamodel'} . '" target="_blank">' . $post->{'datamodel'} . '</a>' . 
				'</div>';
			
		?>
		<article <?php post_class(); ?>>
		<header>
		  <h1 class="entry-title"><?php echo $vac_title; ?></h1>
		  <?php get_template_part('templates/entry-meta','vac'); ?>
			<div class="row">
				<div class="col-xs-12 col-md-6"><?php echo $the_category; ?></div>
				<div class="col-xs-12 col-md-6"><?php echo $announcement; ?></div>
				<?php echo "<div class='clearfix'></div>"?>
				<div class="col-xs-12"><?php echo $the_sas; ?></div>
				<div class="col-xs-12"><?php echo $datamodel; ?></div>
			</div>
		</header>
		<div class="clearfix"></div>
		<div class="entry-content">
		<?php
			echo $www_url;
			the_content(); 
			echo $post_modified; 
		?>
		<div class="clearfix"></div>
		<h2>Publications</h2>
		<?php
			echo get_pub_list( $the_pubs );
		?>
		<div class="row">
		<div class="col-xs-12">
		<ol class="breadcrumb">
		  <li><a href="/">SDSS</a></li>
		  <li><a href="/value-added-catalogs/">Value Added Catalogs</a></li>
		  <li><?php echo $post -> post_title; ?></li>
		</ol>
		</div>
		</div>
		</div>
		</article>
		<?php 
		endwhile;
	}

} else {
	$msg = "An internal error has occurred. ";
	$error_level="danger";
}

if ( $msg ) echo "&nbsp;<br><div class='alert alert-$error_level'>" . $msg . "</div>";
		

