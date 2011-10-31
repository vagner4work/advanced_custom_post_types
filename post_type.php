<?php
class post_type extends acpt {
	/**
	 * Make Post Type
	 * 
	 * @param string -> post type name
	 * @param array -> settings
	 * @param string -> singular name
	 * @param string -> plural name
	 * 
	 * $settings = array(
	 *	'public' => true,
	 *	'has_archive' => true,
	 *	'show_ui' => true,
	 *	'taxonomies' => array( ... ),
	 *	'supports' => array( ... ),
	 *	'hierarchical' => true,
	 * );
	 *
	 *
	 */
	function make($post_type, $settings, $singlular, $plural) {
		// Test for param
		if(!$post_type) exit('Making Post Type: You need to enter a post type.');
		if(!$settings) exit('Making Post Type: You need to enter a settings.');
		if(!$singlular) exit('Making Post Type: You need to enter a singular name.');
		if(!$plural) exit('Making Post Type: You need to enter a plural name.');
		
		$labels = array(
			'name' => ucwords( $plural),
			'singular_name' => ucwords($singlular),
			'add_new' => 'Add New',
			'add_new_item' => 'Add New '.ucwords($singlular),
			'edit_item' => 'Edit '.ucwords($singlular),
			'new_item' => 'New '.ucwords($singlular),
			'view_item' => 'View '.ucwords($singlular),
			'search_items' => 'Search '.ucwords($plural),
			'not_found' =>  'No '.$plural.' found',
			'not_found_in_trash' => 'No '.$plural.' found in Trash', 
			'parent_item_colon' => '',
			'menu_name' => ucwords($plural)
		);
		
		$capabilities = array(
			'publish_posts' => 'publish_'.$plural,
			'edit_post' => 'edit_'.$singlular,
			'edit_posts' => 'edit_'.$plural,
			'edit_others_posts' => 'edit_others_'.$plural,
			'delete_post' => 'delete_'.$singlular,
			'delete_posts' => 'delete_'.$plural,
			'delete_others_posts' => 'delete_others_'.$plural,
			'read_post' => 'read_'.$singlular,
			'read_private_posts' => 'read_private_'.$plural,
		);
		
		$args = array(
			'labels' => $labels,
			'description' => $plural,
			// 'capability_type' => $singlular,
			// 'capabilities' => $capabilities,
			'rewrite' => array( 'slug' => $plural),
			
			'public' => $settings['public'], // Boolean
			'has_archive' => $settings['has_archive'], // Boolean
			'hierarchical' => $settings['hierarchical'], // Boolean
			'show_ui' => $settings['show_ui'], // Boolean
			'taxonomies' => $settings['taxonomies'], // Array
			'supports' => $settings['supports'], // Array
		);
		
		// Register post type
		register_post_type( $post_type, $args);
		
	}
	
	/**
	 * Make Meta Box
	 * 
	 * @param array
	 */
	function meta($defaults) {
	}
}