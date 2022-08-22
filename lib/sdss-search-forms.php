<?php 
function header_search_form( $form ) {
	$form = ?>
	<div class="row"><div class="col-xs-12  align-right hidden">
	<form role="search" method="get" class="search-form form-inline" action="/">
		<div class="input-group">
				<input type="search" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" class="search-field form-control" placeholder="<?php _e('Search', 'roots'); ?> <?php echo $_SERVER['SERVER_NAME']; ?>">
			<label class="hide"><?php _e('Search for:', 'roots'); ?></label>
			<span class="input-group-btn">
			<button type="submit" class="search-submit btn btn-default"><?php _e('Search', 'roots'); ?></button>
			</span>
		</div>
	</form>
	</div></div>
	<?php 
	return $form;
}

add_filter( 'get_search_form', 'header_search_form' );