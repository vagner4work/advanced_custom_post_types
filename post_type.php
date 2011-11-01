<?php
class post_type extends acpt {
	
	/**
	 * Make Post Type. Do not use before init.
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
	 * @param string $post_type post type name
	 * @param array $settings settings
	 * @param string $singular singular name
	 * @param string $plural plural name
	 */
	function make($post_type, $settings, $singular, $plural) {
		// Test for param
		if(!$post_type) exit('Making Post Type: You need to enter a post type.');
		if(!$settings) exit('Making Post Type: You need to enter a settings.');
		if(!$singular) exit('Making Post Type: You need to enter a singular name.');
		if(!$plural) exit('Making Post Type: You need to enter a plural name.');
		
		$upperPlural = ucwords( $plural);
		$upperSingular = ucwords($singular);

		$labels = array(
			'name' => $upperPlural,
			'singular_name' => $upperSingular,
			'add_new' => 'Add New',
			'add_new_item' => 'Add New '.$upperSingular,
			'edit_item' => 'Edit '.$upperSingular,
			'new_item' => 'New '.$upperSingular,
			'view_item' => 'View '.$upperSingular,
			'search_items' => 'Search '.$upperPlural,
			'not_found' =>  'No '.$plural.' found',
			'not_found_in_trash' => 'No '.$plural.' found in Trash', 
			'parent_item_colon' => '',
			'menu_name' => $upperPlural,
		);
		
		$capabilities = array(
			'publish_posts' => 'publish_'.$plural,
			'edit_post' => 'edit_'.$singular,
			'edit_posts' => 'edit_'.$plural,
			'edit_others_posts' => 'edit_others_'.$plural,
			'delete_post' => 'delete_'.$singular,
			'delete_posts' => 'delete_'.$plural,
			'delete_others_posts' => 'delete_others_'.$plural,
			'read_post' => 'read_'.$singular,
			'read_private_posts' => 'read_private_'.$plural,
		);
		
		$args = array(
			'labels' => $labels,
			'description' => $plural,
			// 'capability_type' => $singular,
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