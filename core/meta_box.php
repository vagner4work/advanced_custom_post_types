<?php
if(isset($acpt_version)) :
class meta_box extends acpt {

	function __construct() {
		add_action('save_post',array($this, 'save_form'));
	}
	
	function make($name=null, $settings=array('context' => 'normal', 'priority' => 'high')) {
		if(!$name) exit('Making Meta Box: You need to enter a name.');

		$computerName = $this->make_computer_name($name); 
		foreach ( (array)get_post_types( array( 'public' => true ) ) as $type ) {
			if ( post_type_supports( $type, $computerName ) ) {
				add_meta_box(
					$computerName, 
					$name, 
					$computerName, 
					$type, 
					$settings['context'], 
					$settings['priority']
				);
			}
		}
	}

	function save_form($postID) {
		global $post;
		// called after a post or page is saved 
		if($parent_id = wp_is_post_revision($postID)) $postID = $parent_id;
		// Loop through custom fileds
		foreach($_POST as $cf_name => $cf_data) { 
			// only new meta
			if( preg_match('/^acpt_.*/' , $cf_name) ) { // change to your prefix
				// sanitize data from custom fields
				$cf_data = trim($_POST[$cf_name]); $cf_data = esc_sql($cf_data);
				
				$cf_meta = get_post_meta($postID, $cf_name, true);
				if ($cf_data) { // add and update
					if(!$cf_meta) { add_post_meta($postID, $cf_name, $cf_data); }
					elseif($cf_data != $cf_meta) { update_post_meta($postID, $cf_name, $cf_data); }
				} // delete
				elseif($cf_data == "" && isset($cf_meta)) { delete_post_meta($postID, $cf_name); }
			}
		} // end foreach
	}
}
endif;