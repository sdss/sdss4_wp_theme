<div id="footer" class="content-info" role="contentinfo">
<div class="social">
	<div class="container">
		<?php dynamic_sidebar('sidebar-footer'); ?>
	</div>
	</div>		
</div>
<?php if ( defined ( 'USEBACKUP' ) && USEBACKUP ) echo "<!-- BACKUP -->"; ?>
<?php wp_footer(); ?>