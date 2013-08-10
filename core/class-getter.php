<?php

class acpt_get {

  /**
   * Get Meta Data
   *
   * Get meta data using custom syntax with brackets.
   *
   * @param string $name
   * @param string $fallBack
   * @param bool $groups
   * @param null $id
   *
   * @return mixed|null|string
   */
  static function meta($name = '', $fallBack = '', $groups = true, $id = null) {
    if(!acpt_validate::bracket($name)) die("ACPT Error: You need to use brackets [{$name}]");

    do_action('start_acpt_meta', $name, $fallBack, $id);

    global $post;
    empty($id) ? $id = $post->ID : true;

    if($groups === true ) :
      $data = self::get_groups($name, $id);
    else :
      $data = self::get_single($name, $id);
    endif;

    do_action('end_acpt_meta', $data);

    empty($data) ? $data = $fallBack : true;

    return $data;
  }

  private static function get_groups($name, $id) {
    $data = get_post_meta($id);

    if(!empty($data)) :
      $data = acpt_get::get_meta_data($name, $data);
    else :
      $data = null;
    endif;

    return $data;
  }

  private static function get_single($name, $id) {
    $data = get_post_meta($id, substr($name, 1, -1), true);

    if(empty($data)) :
      $data = "ACPT ERROR: Meta is grouped not single {$name}";
    endif;

    return $data;
  }

  private static function get_meta_data($name, $data) {
    $groups = acpt_utility::groups_to_array($name);

    return self::get_data_by_groups($groups, $data);
  }

  private static function get_data_by_groups($groups, $data) {

    $key = $groups[0];

    if( isset($data[$key][0]) ) :
      $data = maybe_unserialize( $data[$key][0] );
    else :
      return null;
    endif;

    $c = count($groups);

    for ($i = 1; $i < $c; $i++) :

      if(!empty($data[$groups[$i]])) :
        $data = $data[$groups[$i]];
      else :
        $data = null;
        break 1;
      endif;

    endfor;

    return $data;
  }

}