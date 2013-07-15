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
		if(!empty($_POST['save_acpt']) && check_admin_referer('nonce_actp_nonce_action','nonce_acpt_nonce_field')) :
		global $post;

		// called after a post or page is saved
		if($parent_id = wp_is_post_revision($postID)) $postID = $parent_id;
		// Loop through custom fields
		foreach($_POST as $cf_name => $cf_data) {
			// only new meta
			if( preg_match('/^acpt_.*/' , $cf_name) ) { // change to your prefix
				// validate data from custom fields
				$cf_data = trim($_POST[$cf_name]);
				$cf_data = acpt::validate($cf_name, $cf_data);

				$cf_meta = get_post_meta($postID, $cf_name, true);
				if ($cf_data) { // add and update
					if(!$cf_meta) { add_post_meta($postID, $cf_name, $cf_data); }
					elseif($cf_data != $cf_meta) { update_post_meta($postID, $cf_name, $cf_data); }
				} // delete
				elseif($cf_data == "" && isset($cf_meta)) { delete_post_meta($postID, $cf_name); }
			}
		} // end foreach
		endif; // end nonce
	}

	/*
	 * Validate Types
	 *
	 * 0 -> sql
	 * 1 -> text
	 * 2 -> date
	 * 3 -> url
	 * 4 -> img
	 * default - > none
	 * */
	static function validate($key, $value) {

		if( preg_match('/^acpt_v0.*/' , $key) ) {
			$value = $value;
		}
		else if( preg_match('/^acpt_v1.*/' , $key) ) {
			$value = sanitize_text_field($value);
		}
		else if( preg_match('/^acpt_v2.*/' , $key) ) {
			$html = force_balance_tags($value);
			$value = $html;
		}
		else if( preg_match('/^acpt_v3.*/' , $key) ) {
			$value = esc_url($value);
		}
		else if( preg_match('/^acpt_v4.*/' , $key) ) {
			$value = esc_url($value);
		}
		else {
			$value = $value;
		}
		return $value;
	}

	static function apply_css() {
		wp_register_style( 'acpt-styles', ACPT_LOCATION . '/'.ACPT_FOLDER_NAME.'/core/css/style.css' );
		wp_enqueue_style( 'acpt-styles' );
	}

	static function upload_scripts() {

		wp_register_script('fields', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/fields.js', array('jquery'));
		wp_enqueue_script('fields');

		wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ) );

		wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
		wp_enqueue_style( 'jquery-ui' );

		if(function_exists( 'wp_enqueue_media' )){
		    wp_enqueue_media();
		    wp_register_script('upload', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/upload-3.5.js', array('jquery'));
				wp_enqueue_script('upload');
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
			wp_register_script('upload', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/upload.js', array('jquery','media-upload','thickbox'));
			wp_enqueue_script('upload');
		}
	}

}