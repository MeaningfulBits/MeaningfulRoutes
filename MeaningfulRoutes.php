<?php
/*
Plugin Name: Meaningful Routes
Plugin URI: https//www.MeaningfulBits.io
Description: A plugin to add random endpoints.
Version: 0.1
Author: T. Thomas
Author URI: https://www.MeaningfulBits.io
License: GPL2
*/

//Start Custom Code for a Random Endpoints
function random_endpoint() {
	add_rewrite_endpoint( 'random', EP_ROOT );
}
add_action( 'init', 'random_endpoint' );

function random_redirect() {
	// If we have accessed our /random/ endpoints.
	$post_type = get_query_var( 'random' );
	if ( $post_type == '' ) {
		$post_type = [ 'post', 'page', 'product' ];
	}
	$query_string = "";
	$arr = explode("?",$_SERVER['REQUEST_URI']);
	if (count($arr) == 2){
		$query_string = "?".end($arr);
	}
	if ( get_query_var( 'random', false ) !== false ) {
		// Get a random post.
		$random_post = get_posts( [
			'numberposts' => 1,
			'post_type'   => $post_type,
			'orderby'     => 'rand',
		] );
		// If we found one.
		if ( ! empty( $random_post ) ) {
			// Get its URL.
			$url = esc_url_raw( get_the_permalink( $random_post[0] ) );
			// Escape it.
			$url = esc_url_raw($url.$query_string);
			// Redirect to it.
			wp_safe_redirect( $url, 302 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'random_redirect' );
//End Custom Code for a Random Endpoints

?>
