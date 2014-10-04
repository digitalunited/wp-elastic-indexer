<?php
/*
Plugin Name: WP Elasticsearch Indexer
Description: Post indexer example plugin for wp-elastic-api
Author: Digital United
Version: 0.1
Author URI: http://www.careofhaus.io/
*/

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

add_action( 'save_post', 'wp_elastic_indexer_save_post' );

function wp_elastic_indexer_save_post( $post_id ) {

    $data = get_object_vars( get_post( $post_id ) );

    $body = array(
        'post_type' => 'post',
        'ID' => $post_id,
        'data' => $data
    );

    \Httpful\Request::post( 'http://wpelastic.dev/app/plugins/wp-elastic-api/posts' )->body( json_encode( $body ) )->send();

}