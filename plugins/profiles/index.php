<?php

class acpt_current_user {

  static public $id = null;
  static public $meta = null;
  static public $user_meta = null;
  static public $name = null;
  static public $photo = null;
  static public $role = null;
  static public $email = null;
  static public $joined = null;
  static public $gallery = null;

  static function hooks() {
    add_filter('get_avatar', 'acpt_current_user::filter_avatar', 9999, 3);
  }

  static function setup($profile_id = null) {

    self::$id = $profile_id;
    self::$meta = get_userdata(self::$id);
    self::$role = self::$meta->roles[0];
    self::$gallery = acpt_user_meta('[user_gallery]', self::$id);
    self::$joined = strtotime(self::$meta->user_registered);
    self::$email = self::$meta->data->user_email;
    self::$user_meta = get_user_meta(self::$id);
    self::$name = self::$meta->first_name . ' ' . self::$meta->last_name;

  }

  static function get_data($var) {
    return self::$$var;
  }

  static function get_default_avatar_url() {
    return get_stylesheet_directory_uri() . '/' . ACPT_FOLDER_NAME . '/plugins/profiles/img/default-avatar.png';
  }

  static function get_avatar($profile_id = null, $size = 75, $default = '', $alt = null) {
    if($profile_id == null) {
      return false;
    }

    $id = acpt_user_meta('[profile_avatar_id]', $profile_id);

    if(is_string($size)) {
      $size = (int) $size;
    }
    if(is_int($size)) {
      $size = array($size,$size);
    }
    return wp_get_attachment_image( $id , $size, false, array('class' => 'avatar profile-avatar') );
  }

  static function filter_avatar($avatar, $id_or_email = null, $size) {

    $img = self::get_avatar($id_or_email, $size);

    if(!empty($img)) {
      $avatar = $img;
      unset($img);
    }

    return $avatar;
  }

}

class acpt_profile {

  static $query_var = 'person';
  static $archive_page = 'profiles';

  static function hooks() {
    add_action( 'template_redirect', 'acpt_profile::template_redirect');
    add_filter('rewrite_rules_array', 'acpt_profile::rewrite_rules_array');
    add_filter('query_vars', 'acpt_profile::query_vars');
    add_action( 'wp_enqueue_scripts', 'acpt_profile::wp_enqueue_scripts' );
  }

  static function get_the_profile_url($profile_id = '') {
    return home_url(  '/' . self::$archive_page . '/' . self::$query_var . '/' . $profile_id );
  }

  static function get_the_profile_page_url() {
    return home_url( '/' . self::$archive_page . '/' );
  }

  static function the_form($profile_id = null) {
    include 'form.php';
  }

  static function title($title) {
    return  'Team | ';
  }

  static function the_page($profile_id = null) {
    add_filter( 'wp_title', 'acpt_profile::title' );
    get_template_part('templates/head');
    get_template_part('templates/header');
    include 'page-profile.php';
    get_template_part('templates/footer');
  }

  static function the_archive($query_vars = null) {
    add_filter( 'wp_title', 'acpt_profile::title' );
    get_template_part('templates/head');
    get_template_part('templates/header');
    include 'archive-profile.php';
    get_template_part('templates/footer');
  }

  static function wp_enqueue_scripts() {
    $plugin_dir = get_stylesheet_directory_uri() . '/' . ACPT_FOLDER_NAME . '/plugins/profiles/';

    wp_enqueue_style( 'profile-fancy-css', $plugin_dir . 'fancy/jquery.fancybox.css' );
    wp_enqueue_script( 'profile-fancy-js', $plugin_dir . 'fancy/jquery.fancybox.pack.js', array('jquery'), '1.0.0', true  );

    wp_enqueue_style( 'profile-css', $plugin_dir . 'css/profiles.css' );
    wp_enqueue_script( 'profile-js', $plugin_dir . 'js/profiles.js' );
  }

  static function get_sort_query_vars($meta_key) {
    $order = sanitize_text_field($_GET['order']);

    if($order == 'ASC') {
      $order = 'DESC';
    } else {
      $order = 'ASC';
    }

    if(isset($_GET["as"])) {
      $query = sanitize_text_field($_GET["as"]);
      $query = '&as=' . $query;
    } else {
      $query = '';
    }

    return "?sort={$meta_key}&order={$order}" . $query;

  }

  static function get_query_string($type = '?') {
    return $type . $_SERVER["QUERY_STRING"];
  }

  // Register a new var
  static function query_vars( $vars) {
    $vars[] = self::$query_var; // name of the var as seen in the URL
    return $vars;
  }

  // Add the new rewrite rule to existings ones
  static function rewrite_rules_array($rules) {

    $regx = self::$archive_page . '/' . self::$query_var.'/([^/]+)/?$';
    $uri = 'index.php?'.self::$query_var.'=$matches[1]';

    $new_rules = array( $regx => $uri);
    $rules = $new_rules + $rules;
    return $rules;
  }

  // Retrieve URL var
  static function template_redirect() {
    global $wp_query;

    if(isset($wp_query->query_vars[self::$query_var])) {
      acpt_profile::the_page(get_query_var(self::$query_var));
      exit();
    }
    elseif($wp_query->query_vars['pagename'] == self::$archive_page) {
      acpt_profile::the_archive($wp_query->query_vars);
      exit();
    }

  }

}

acpt_current_user::hooks();
acpt_profile::hooks();