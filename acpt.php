<?php
/**
* Advanced Custom Post Types
*
* @global string $acpt_version
*/

if($wp_version < '3.3' || $wp_version == null ): exit('You need the 3.3 version of WordPress.');
else: $acpt_version = '0.6';
endif;

class acpt {
	
	function __construct() {
		// Set custom post type messages
		// Find in /wp-admin/edit-form-advanced.php
		$func = function($messages) {
			global $post, $post_ID;
			$post_type = get_post_type( $post_ID );
			
			$obj = get_post_type_object($post_type);
			$singular = $obj->labels->singular_name;
			
			$messages[$post_type] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __($singular.' updated. <a href="%s">View '.strtolower($singular).'</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __($singular.' updated.'),
			5 => isset($_GET['revision']) ? sprintf( __($singular.' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __($singular.' published. <a href="%s">View '.strtolower($singular).'</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Page saved.'),
			8 => sprintf( __($singular.' submitted. <a target="_blank" href="%s">Preview '.strtolower($singular).'</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __($singular.' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview '.strtolower($singular).'</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __($singular.' draft updated. <a target="_blank" href="%s">Preview '.strtolower($singular).'</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			);
			return $messages;
		};
		add_filter('post_updated_messages', $func);
	}

	function make_computer_name($name) {
		$pattern = '/(\s+)/';
		$replacement = '_';
		$computerName = preg_replace($pattern,$replacement,strtolower(trim($name)));
		return $computerName;
	}
}

// Make Post Type
include('core/post_type.php');

// Make Tax
include('core/tax.php');

// Make Role
include('core/role.php');

// Make Role
include('core/form.php');

// Make Role
include('core/meta_box.php');