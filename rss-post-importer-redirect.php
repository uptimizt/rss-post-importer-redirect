<?php
/*
Plugin Name: RSS Post Importer Redirect
Version: 0.1
Plugin URI: https://github.com/yumashev/rss-post-importer-redirect
Description: Get url from meta 'rss_pi_source_url' and redirect via hook 'template_redirect'. Support plugin https://ru.wordpress.org/plugins/rss-post-importer/
Author: AY
Author URI: https://github.com/yumashev/
*/


class RSS_URL_Redirect {

  function __construct() {
    add_action( "template_redirect", array( $this, "rss_url_redirect" ) );

      add_filter( 'post_link', [$this, 'chg_url'], 10, 3 );
  }

  function chg_url( $url, $post, $leavename=false ) {
      if ( $post->post_type != 'post' ) {
        return $url;
      }

      $url_rss = get_post_meta($post->ID, 'rss_pi_source_url', true);
      if( empty($url_rss) or ! filter_var($url_rss, FILTER_VALIDATE_URL) ){
        return $url;
      }

      return $url_rss;
  }


  function rss_url_redirect(){

    if ( ! defined('RSS_PI_PATH') ) {
      return;
    }

    if( ! is_singular('post')){
      return;
    }

    $post = get_post();
    $url = get_post_meta($post->ID, 'rss_pi_source_url', true);

    if( empty($url) or ! filter_var($url, FILTER_VALIDATE_URL) ){
      return;
    }

    wp_redirect($url, 301);
    exit;
  }
}

  new RSS_URL_Redirect;
