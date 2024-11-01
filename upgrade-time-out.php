<?php
/*
Plugin Name: Wordpress/Plugin Upgrade Time Out Plugin
Plugin URI: http://felixker.com/wp-timeout-plugin/
Description: Wordpress/Plugin Upgrade Time Out Plugin allows you to modify the files download time out value (mainly used for upgrade of Wordpress and Plugins). No more requiring to modify after every Wordpress upgrade.
Author: Felix Ker
Author URI: http://felixker.com
Version: 1.0
*/


add_action('admin_menu', 'timeout_options_page');


function timeout_options_page() {
	if (function_exists('add_options_page')) {
		add_options_page('Upgrade TimeOut', 'Upgrade TimeOut', 8, basename(__FILE__), 'timeout_options_panel');
	}
}
function getValues(){
global $timeOutValue;
$timeOutValue = get_option('timeOutValue');

if ( $timeOutValue  == '' || !isset($timeOutValue) ){
	$timeOutValue  = 60;
}
	return $timeOutValue;
}

function timeout_options_panel() {
	$currentTimeout = getValues();
	if ( isset( $_POST['timeout'] ) ) {
		if ( is_int( (int)$_POST['timeout'] ) ){
			$currentTimeout = $_POST['timeout'];
			update_option( 'timeOutValue', $_POST['timeout'] );
			$isUpdated = 'Your setting has been saved.';
		} else {
			echo '<div class="wrap"><p>You may only enter numbers.</p></div>';
		}
	}

	if ($isUpdated != '') echo '<div id="message" class="updated fade"><p>' . $isUpdated . '</p></div>';
	
	echo '<div class="wrap">';
	echo '<h2>Change your Plugins/Wordpress Upgrade Time Out Value</h2>';
	echo '<p>This plugin allows you to change the time out value at anytime you think the file is getting too huge and you eventually receive time out.</p>
	<form action="" method="post">
	<p>Time out Value: 
	<input type="text" name="timeout" value="' . $currentTimeout . '" size="5" />
	<br />
	(Somewhere 60 - 120 is a safe value.)</p>
	<p><input type="submit" value="Save" /></p></form>';
	echo '</div>';

}

function filter_timeout($r)
{
$currentTimeout = getValues();
       if ( $r['timeout'] == 30)
               $r['timeout'] = $currentTimeout;
       return ($r);
}

add_filter('http_request_args','filter_timeout');

