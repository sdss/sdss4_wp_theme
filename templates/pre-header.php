<?php 
/*
 * Bonnie Souter 03/08/2016
 * Moved pre-header info from templates to consolidate identical function calls.
 */
?>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-M6F94N"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-M6F94N');</script>
<!-- End Google Tag Manager -->
<!-- Google Site Verification -->
<meta name="google-site-verification" content="QiuHPO75GhVOxJmQ7-d0YNYur_QXFlfLN4p4hHzRwRg" />
<!-- End Google Site Verification -->
  <!--[if lt IE 8]>
    <div class="alert alert-warning">
      <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
    </div>
  <![endif]-->
<?php 
if ( defined( 'WP_ENV' ) && 
	 defined( 'DATA_RELEASE' ) && 
	( strcmp( WP_ENV , 'development' ) === 0 ) ) : 
?>
	<div class="wrap container-fluid" role="document">
		<div class="content row">
			<div class="col-xs-12" style="background-color: orange;">
				<p class="h1 pull-left"><?php echo DATA_RELEASE ; ?></p>
				<p class="pull-right align-right">&nbsp;<br><?php
				if (! is_user_logged_in()) {
					echo( '<span><a href="/wp-login.php" class="btn btn-primary">Log In</a></span>' );
				} else {
					//echo( '<span><a href="/wp-login.php?action=logout" class="btn btn-danger">Log Out</a><span>' );
				}?></p>
			</div>
			<div class="clearfix"><div>
		</div>
	</div>
<?php
endif;
?>
