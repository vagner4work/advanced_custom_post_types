<?php

class acpt_save extends acpt {

  static function save_post_fields($postID) {
    if(isset($_POST['save_acpt']) && check_admin_referer('nonce_actp_nonce_action','nonce_acpt_nonce_field')) :
      do_action('start_acpt_save');
      $metaData = apply_filters('acpt_save_filter', $_POST['acpt']);

      // called after a post or page is saved
      if($parent_id = wp_is_post_revision($postID)) $postID = $parent_id;
      foreach($metaData as $key => $value) :

        $value = trim($value);
        $current_meta = get_post_meta($postID, $key, true);

        if ($value && !$current_meta || $value != $current_meta ) :
          update_post_meta($postID, $key, $value);
        elseif( empty($value) && isset($current_meta)) :
          delete_post_meta($postID, $key);
        endif;

      endforeach;

      do_action('end_acpt_save');
    endif; // end nonce
  }

}