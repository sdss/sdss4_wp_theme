<header role="banner">
	<div class="navbar navbar-inverse navbar-static-top" >
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="navbar-header">
						<!-- Button for smallest screens -->
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> 
						</button>
						<div class="hidden-xs">
							<a class="navbar-brand" href="/">
							<img src="/wp-content/uploads/2014/05/sdsslogowhite.png" alt="sdsslogo" class="img-responsive">
							</a>
						</div>
						<div class="visible-xs">
							<img src="/wp-content/uploads/2014/05/sdsslogowhite.png" alt="sdsslogo" style="width:200px">
						</div>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="navbar-collapse collapse pull-right" role="navigation">
						<?php
						if (has_nav_menu('primary_navigation')) :
						  wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav pull-right'));
						endif;
						?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3 text-left">
					<span class="data-release"><?php 
					echo "This&nbsp;is&nbsp;" . DATA_RELEASE . ".";
					if ( !defined('LATEST') ) echo "<br><small><em>Go&nbsp;to&nbsp;the&nbsp;latest&nbsp;<a href='//www.sdss.org/data/' target='_blank'>Data&nbsp;Release</a>.</em></small>"; 
					?><br />See our full list of <a href="/data-releases">data releases</a>.</span>
					
				</div>
				<div class="col-sm-9 col-md-4 col-md-offset-5 text-right">
				<?php get_search_form(); ?>
				</div>
			</div>
		</div>
    </div>
</header>