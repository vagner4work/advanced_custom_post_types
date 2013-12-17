<?php
/**
 * Class acpt_save
 *
 * Functions for saving data to the WP DB
 */
class acpt_save extends acpt {

  /**
   * Save Post Data
   *
   * Save data when a post type is being created or updated.
   *
   * @param $postID
   * @param $form
   */
  static function save_post_fields($postID, $form = null) {
    if(isset($_POST['save_acpt']) && check_admin_referer('nonce_actp_nonce_action','nonce_acpt_nonce_field')) :
      /*
       * Action start_acpt_save
       *
       * Do anything with $_POST data before saving.
       */
      do_action('start_acpt_save', $_POST);

      /*
       * Filter acpt_save_filter
       *
       * Filter data before it is run though the saving process.
       * Access is given to acpt array data from $_POST only.
       */
      unset($_POST['acpt']['insert']);

      $metaData = apply_filters('acpt_save_filter', $_POST['acpt']);

      if(is_integer($postID)) {
        self::save_post_meta($postID, $metaData);
      } elseif($postID === 'options') {
        self::save_options_meta($metaData);
      } elseif($postID === 'user_meta') {

        if(isset($form->user)) {
          $user_id = $form->user;
        } else {
          $user_id = null;
        }

        self::save_user_meta($metaData, $user_id, $form);
      }


      /*
       * Action end_acpt_save
       *
       * Do anything with $_POST data after saving is over
       */
      do_action('end_acpt_save', $_POST);
    endif; // end nonce
  }

  private static function save_post_meta($postID, $metaData) {
    // called after a post or page is saved
    if($parent_id = wp_is_post_revision($postID)) $postID = $parent_id;
    foreach($metaData as $key => $value) :

      if(is_string($value)) $value = trim($value);

      $current_meta = get_post_meta($postID, $key, true);

      if (isset($value) && !$current_meta || $value != $current_meta ) :
        update_post_meta($postID, $key, $value);
      elseif( !isset($value) && isset($current_meta)) :
        delete_post_meta($postID, $key);
      endif;

    endforeach;
  }

  private static function save_options_meta($metaData) {
    foreach($metaData as $key => $value) :

      if(is_string($value)) $value = trim($value);

      $current_meta = get_option($key);

      $data['alert'] = "Changes saved.";
      $data['type'] = 'updated';

      if ( isset($value) && $current_meta === false ) :
        add_option( $key, $value, '', 'yes' );
        set_transient('acpt_options_updated', $data, 0 );
      elseif (isset($value) && $value != $current_meta ) :
        update_option( $key, $value );
        set_transient('acpt_options_updated', $data, 0 );
      elseif( empty($value) && isset($current_meta)) :
        delete_option( $key );
        set_transient('acpt_options_updated', $data, 0 );
      endif;

    endforeach;
  }

  private static function save_user_meta($metaData, $user_id, $form = null) {
    if(!current_user_can('edit_users') || !isset($user_id) ) :
      $user_id = get_current_user_id();
    endif;

    if(!empty($metaData['user_insert']) && is_array($metaData['user_insert'])) {
      $args = array('ID' => $user_id);
      foreach($metaData['user_insert'] as $k => $v) {
        if($k == 'user_pass' && !empty($v) && $v == $metaData['user_insert']['user_pass_confirm']) {
          $args[$k] = $v;
        } elseif($k != 'user_pass') {
          $args[$k] = $v;
        }
      }
      wp_update_user( $args );
      unset($metaData['user_insert']);
    }

    foreach($metaData as $key => $value) :

      if(is_string($value)) $value = trim($value);

      $current_meta = get_user_meta($user_id, $key);

      $data['alert'] = "Changes saved.";
      $data['type'] = 'updated';

      if ( isset($value) && $current_meta === false ) :
        add_user_meta($user_id, $key, $value );
        set_transient('acpt_options_updated', $data, 0 );
      elseif (isset($value) && $value != $current_meta ) :
        update_user_meta($user_id, $key, $value );
        set_transient('acpt_options_updated', $data, 0 );
      elseif( empty($value) && isset($current_meta)) :
        delete_user_meta($user_id, $key );
        set_transient('acpt_options_updated', $data, 0 );
      endif;

    endforeach;

  }

}
