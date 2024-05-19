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
	global $wp;

	// Get post type from endpoint
	$query_params = explode( '/', $wp->request );

	// If users isn't requesting a '/random' post, return
	if ( ! isset( $query_params[0] ) || ( isset( $query_params[0] ) && $query_params[0] !== 'random' ) ) {
		return;
	}


	// If no post type is set, use all
	if ( ! isset( $query_params[1] ) ) {
		$post_type = [ 'post', 'page', 'product' ];
	} else {
		$post_type = $query_params[1];
	}

	// Set query string to be appended on redirect
	$query_string = "";
	$arr          = explode( "?", $_SERVER['REQUEST_URI'] );
	if ( count( $arr ) == 2 ) {
		$query_string = "?" . end( $arr );
	}

	$required_tag = '';
	$required_cat = '';

	// If the user has set a category/tag set and a parameter is passed for it
	if ( isset( $query_params[1], $query_params[2] ) && $query_params[2] === 'cat' ) {
		$required_cat = sanitize_title( $query_params[2] );
	} else if ( isset( $query_params[1], $query_params[2] ) && $query_params[2] === 'tag' ) {
		$required_tag = $query_params[2];
	}


	// Get a random post.
	$random_post = get_posts( array(
		'numberposts'   => 1,
		'post_type'     => $post_type,
		'tag'           => array( $required_tag ),
		'category_name' => $required_cat,
		'orderby'       => 'rand',
	) );


	// If we found one.
	if ( ! empty( $random_post ) ) {
		// Get its URL.
		$url = esc_url_raw( get_the_permalink( $random_post[0] ) );
		// Escape it.
		$url = esc_url_raw( $url . $query_string );
		// Redirect to it.
		wp_safe_redirect( $url, 302 );
		exit;
	}
	

} // end random redirect function
add_action( 'template_redirect', 'random_redirect' );
//End Custom Code for a Random Endpoints

?>
