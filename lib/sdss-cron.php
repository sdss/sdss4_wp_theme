<?php
/**
 * Set up wp-cron schedules and tasks
 */

/****************************************************/
/*                ADD CRON SCHEDULES                */
/****************************************************/
add_filter( 'cron_schedules', 'sdss_add_cron_intervals' );
function sdss_add_cron_intervals( $schedules ) {
    /*
	$schedules['fiveseconds'] = array(
        'interval' => 5,
        'display'  => esc_html__( 'Every 5 Seconds' ),
    );
	*/
    $schedules['oneminute'] = array(
        'interval' => 60,
        'display'  => esc_html__( 'Every 1 Minute' ),
    );
    $schedules['fiveminute'] = array(
        'interval' => 300,
        'display'  => esc_html__( 'Every 5 Minutes' ),
    );
    return $schedules;
}

/****************************************************/
/*                DELETE OLD CRON EVENTS                */
/****************************************************/
wp_clear_scheduled_hook('sdss_test_email');
wp_clear_scheduled_hook('sdss_cron_hook_1d');
wp_clear_scheduled_hook('sdss_cron_hook');
wp_clear_scheduled_hook('sdss_cron_hook_5m');
//wp_clear_scheduled_hook('sdss_cron_hook_1m');

/****************************************************/
/*                SET UP CRON EVENTS                */
/****************************************************/
/*
if ( ! wp_next_scheduled( 'sdss_test_email' ) ) {
  wp_schedule_event( time(), 'oneminute', 'sdss_test_email' );
}
*/
if ( ! wp_next_scheduled( 'sdss_cron_hook_5m' ) ) {
	wp_schedule_event( time(), 'fiveminute', 'sdss_cron_hook_5m' );
}
if ( ! wp_next_scheduled( 'sdss_cron_hook_1m' ) ) {
	wp_schedule_event( time(), 'oneminute', 'sdss_cron_hook_1m' );
}

/****************************************************/
/*                ADD CRON TASKS                    */
/****************************************************/
//add_action( 'sdss_cron_hook_5m', 'sdss_cron_minute_exec' );
add_action( 'sdss_cron_hook_1m', 'sdss_cron_minute_exec' );
//add_action( 'sdss_test_email', 'sdss_test_task' );

/****************************************************/
/*                EXECUTE CRON TASKS             */
/****************************************************/
function sdss_cron_minute_exec() {
	$nl =  "<br>\n";
	$msg = "In sdss_cron_minute_exec on " . gethostname();
	$msg .= $nl;
	
	//require_once locate_template('/lib/sdss-parsejsons.php');
	if ( function_exists( sdss_process_jsons() ) ){
		$msg .= "Found function: sdss_process_jsons; ";
		$msg .= $nl;
		wp_mail( 'bsouter@jhu.edu', 'WordPress Cron 1/2', $msg );
		sdss_process_jsons();
		$msg .= "Done with sdss_process_jsons; ";
		$msg .= $nl;
		wp_mail( 'bsouter@jhu.edu', 'WordPress Cron 2/2', $msg );
	}
	
}
function sdss_test_task() {
  wp_mail( 'bsouter@jhu.edu', 'WordPress Cron Test Email', 'Automatic scheduled cron test email from WordPress.');
}
