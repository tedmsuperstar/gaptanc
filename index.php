<?php

/*
Plugin Name: Gaptanc 
Plugin URI: http://tedmetzger.com
Description: Makes "Publish to Apple News"  plugin  compatible with "Guest Author"  plugin. Inserts the guest authors into the byline and metadata before sending to Apple News. Plugin URLs: https://wordpress.org/plugins/publish-to-apple-news/  https://wordpress.org/plugins/guest-author/
Author: Ted Metzger
Version: 1.0
Author URI: http://tedmetzger.com/
*/

class Gaptanc {

  //get the guest author name from the post metatadata if it exists
  public static function get_guest_name($post_id){

    return get_post_meta ($post_id, 'BS_guest_author_name', true);

  }

  //filter the apple news byline with the guest authors
  public static function filter_apple_news_exporter_byline( $byline, $post_id ) {

      $guest_name = Gaptanc::get_guest_name($post_id);

      if(!empty($guest_name)) {
        $date_format = 'F j, Y';
        $post_for_date = get_post($post_id);
        //TODO: use site meta to get formatting from wordpress
        $byline = sprintf(
          'By %1$s on %2$s',
          $guest_name,
          apple_news_date( $date_format, strtotime( $post_for_date->post_date ) )
        );

      }
      return $byline;
  }


  //filter the apple news author metadata with the guest authors
  public static function filter_apple_news_metadata( $meta, $post_id ) {

      $guest_name = Gaptanc::get_guest_name($post_id);

      if(!empty($guest_name)) {
        if(!empty($meta)){
          $meta["authors"] = array($guest_name);
        }
      }
      return $meta;
  }
}

add_filter( 'apple_news_exporter_byline', 'Gaptanc::filter_apple_news_exporter_byline',2,2);
add_filter( 'apple_news_metadata', 'Gaptanc::filter_apple_news_metadata',2,2);



