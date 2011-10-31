<?php
/**
* Advanced Custom Post Types
*
* @global string $acpt_version
*/

if($wp_version < '3.3' || $wp_version == null ): exit('You need the 3.3 version of WordPress.');
else: $acpt_version = '0.0.1';
endif;

class acpt {
	function custom_field($type) {
		switch ($type) {
			case 'text':
				$field = '<input type="text" class="text">';
			case 'textarea' :
				$field = '<textarea class="textarea"></textarea>';
			case 'image' :
				$field = '<input type="text" class="image">';
			case 'editor' :
				$field = '<textarea class="editor"></textarea>';
			default :
				$field = '<input type="text" class="text">';
		}
	}
}

// Make and Meta
include('post_type.php');