<?php
/**
  * Advanced Custom Post Types
  *
  * This is the long description for a DocBlock. This text may contain
  * multiple lines and even some _markdown_.
  *
  * * Markdown style lists function too
  * * Just try this out once
  *
  * The section after the long description contains the tags; which provide
  * structured meta-data concerning the given element.
  *
  * @author  Kevin Dees
  *
  * @since 0.6
  * @version 0.6
  *
  * @global string $acpt_version
  */
class acpt {

	function __construct() {

	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}

	function make_computer_name($name) {
		$pattern = '/(\s+)/';
		$replacement = '_';
		$computerName = preg_replace($pattern,$replacement,strtolower(trim($name)));
		return $computerName;
	}

	static function set_messages($messages) {
		global $post;

		$pt = get_post_type( $post->ID );

		if($pt != 'attachment' && $pt != 'page' && $pt != 'post') :

			$obj = get_post_type_object($pt);
			$singular = $obj->labels->singular_name;

			if($obj->public == true) :
				$view = sprintf( __('<a href="%s">View %s</a>'), esc_url( get_permalink($post->ID)), $singular);
				$preview = sprintf( __('<a target="_blank" href="%s">Preview %s</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ), $singular);
			else :
				$view = $preview = '';
			endif;

			$messages[$pt] = array(
				1 => sprintf( __('%s updated. %s'), $singular , $view),
				2 => __('Custom field updated.'),
				3 => __('Custom field deleted.'),
				4 => sprintf( __('%s updated.'), $singular),
				5 => isset($_GET['revision']) ? sprintf( __('%s restored to revision from %s'), $singular, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6 => sprintf( __('%s published. %s'), $singular, $view ),
				7 => sprintf( __('%s saved.'), $singular),
				8 => sprintf( __('%s submitted. %s'), $singular, $preview ),
				9 => sprintf( __('%s scheduled for: <strong>%1$s</strong>. %s'), $singular, date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ), $preview ),
				10 => sprintf( __('%s draft updated. '), $singular),
			);

		endif;

		return $messages;
	}

	static function save_form($postID) {
		if(isset($_POST['save_acpt']) && check_admin_referer('nonce_actp_nonce_action','nonce_acpt_nonce_field')) :
		global $post;
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

	static function apply_css() {
		wp_register_style( 'acpt-styles', ACPT_LOCATION . '/'.ACPT_FOLDER_NAME.'/core/css/style.css' );
		wp_enqueue_style( 'acpt-styles' );
	}

	static function upload_scripts() {

    wp_enqueue_script('fields', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/fields.js', array('jquery'));
		wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ), '1.0', true );

    wp_enqueue_style('jquery-ui-acpt', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');

		if(function_exists( 'wp_enqueue_media' )){
		    wp_enqueue_media();
        wp_enqueue_script('upload', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/upload-3.5.js', array('jquery'));
				wp_enqueue_script('plupload');
				wp_enqueue_script('media-upload');
				wp_enqueue_style('thickbox');
				wp_enqueue_script('thickbox');
		}
		else {
			wp_enqueue_script('plupload');
			wp_enqueue_script('media-upload');
			wp_enqueue_style('thickbox');
			wp_enqueue_script('thickbox');
      wp_enqueue_script('upload', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/upload.js', array('jquery','media-upload','thickbox'));
		}
	}

}