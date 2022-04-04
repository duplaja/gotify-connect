<?php
/*
Plugin Name: Connect Gotify to WP
Plugin URI: https://dandulaney.com
Description: Allow WP site to send Gotify Notifications
Version: 1.0
Author: Dan Dulaney
Author URI: https://dandulaney.com
License: GPLv3
License URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/duplaja/gotify-connect',
	__FILE__,
	'gotify-connect'
);
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');


if(!function_exists('gotify_send')) {

	function gotify_send($message ='', $title ='', $url='', $token ='') {
		
		if(empty($token)) {
			if(!defined('GOTIFY_SEND_TOKEN')) {
				return false;
			} else {
				$token = GOTIFY_SEND_TOKEN;
			}
		}
		
		if(empty($url)) {
			if(!defined('GOTIFY_URL')) {
				return false;
			} else {
				$url = GOTIFY_URL;
			}
		}
		
		if(empty($title) || empty($message)) {
			return false;
		}

		$send_url = $url.'/message?token='.$token;

		$data = array(
			'priority' => 5,
			'title' => $title,
			'message' => $message
		);

		$args = array(
			'method'      => 'POST',
    		'timeout'     => 45,
    		'redirection' => 5,
    		'httpversion' => '1.0',
    		'blocking'    => true,
    		'headers'     => array(),
			'body' => $data
		);

		$response = wp_remote_post($send_url,$args);

		if ( is_wp_error( $response ) ) {
			return false;
		} else {
			return true;
		}	
	}
}
