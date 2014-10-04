<?php
/*
Plugin Name: WP Elasticsearch Indexer
Description: Post indexer example plugin for wp-elastic-api
Author: Digital United
Version: 0.1
Author URI: http://www.careofhaus.io/
*/

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

add_action( 'save_post', 'wp_elastic_indexer_save_post' );

function wp_elastic_indexer_save_post( $post_id ) {

	$api_url          = 'http://wpelastic.dev/app/plugins/wp-elastic-api';
	$index_post_types = array(
		'post',
		'page'
	);

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	if ( ! isset( $_POST['post_type'] ) ) {
		return $post_id;
	}

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    if ( ! in_array( $_POST['post_type'], $index_post_types ) ) {
		return $post_id;
	}

	$data = array();

	$post = get_object_vars( get_post( $post_id ) );
	$data = array_merge( $data, $post );

	$meta = get_post_meta( $post_id );
	$data = array_merge( $data, $meta );

	$body = array(
		'post_type' => 'post',
		'ID'        => $post_id,
		'data'      => $data
	);

	\Httpful\Request::post( $api_url . '/posts' )->body( json_encode( $body ) )->send();

	return $post_id;

}