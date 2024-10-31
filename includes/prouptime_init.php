<?php

function prouptime_admin_init() {


  $siteurl  = get_site_url();
  $sitename = get_option('blogname');
  $email    = get_option('admin_email');
  $userid   = get_option('prouptime_id');
  $hash     = get_option('prouptime_hash');
  $timezone = wp_timezone_string();
  $locale   = get_locale();

  $content = $userid;

  // Signup Request
  if($_POST['agb'] == '1' && !$userid) {

    $body = array(
        'siteurl'  => $siteurl,
        'sitename' => $sitename,
        'email'    => $email,
        'id'       => $userid,
        'timezone' => $timezone,
        'locale'   => $locale
        );

    $args = array(
        'body' => $body,
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
        );

    $response = wp_remote_post( 'http://prouptime.com/signup', $args );

    $http_code = wp_remote_retrieve_response_code( $response );

    if($http_code == 200) {
      $data = wp_remote_retrieve_body( $response );  
      $data = json_decode($data);
      $userid = $data->id;
      $hash = $data->hash;
      add_option('prouptime_id',$userid);
      add_option('prouptime_hash',$hash);
    }

  }

  // Display Signup-Page
  if(!$userid) {

    $error = ($_POST['p'] == '1') ? '<div class="prouptime_error">Please accept our terms and conditions</div>':'';

    $html = file_get_contents(plugin_dir_path( __FILE__ ) . '../templates/prouptime_signup.html');
    $html = str_replace('%email%',$email,$html);
    $html = str_replace('%siteurl%',$siteurl,$html);
    $html = str_replace('%sitename%',$sitename,$html);
    $html = str_replace('%timezone%',$timezone,$html);
    $html = str_replace('%locale%',$locale,$html);
    $html = str_replace('%error%',$error,$html);

  }
  else {
  // Get statistics from prouptime.com

    $body = array(
        'siteurl'  => $siteurl,
        'sitename' => $sitename,
        'email'    => $email,
        'timezone' => $timezone,
        'locale'   => $locale
        );

    $args = array(
        'body' => $body,
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
        );

    $response = wp_remote_post( 'http://prouptime.com/status/'.$userid.'/'.$hash, $args);

    $content = wp_remote_retrieve_body( $response );  

    if(strpos($content,'User not found') !== false) {
      delete_option('prouptime_id');
      delete_option('prouptime_hash');
    }


    $html = file_get_contents(plugin_dir_path( __FILE__ ) . '../templates/prouptime_admin.html');
    $html = str_replace('%content%',$content,$html);

  }

  echo $html;

}




