<?php
class tax extends acpt {
	
	/**
	* Make Taxonomy. Do not use before init.
	*
	* @param string $singular singular name is required
	* @param string $plural plural name is required
	* @param boolean $hierarchical add hierarchy
	* @param boolean $cap turn on custom capabilities
	* @param string|array $post_type set the post types which to apply taxonomy (null is an option)
	* @param array $settings args override and extend
	*/
	function make($singular = null, $plural = null, $hierarchical = false, $post_type = null, $cap = false, $settings = array() ) {
		if(!$singular) exit('Making Taxonomy: You need to enter a singular name.');
		if(!$plural) exit('Making Taxonomy: You need to enter a plural name.');

		$upperPlural = ucwords($plural);
		$upperSingular = ucwords($singular);
		
		$labels = array(
		    'name' => _x( $upperPlural, 'taxonomy general name' ),
		    'singular_name' => _x( $upperSingular, 'taxonomy singular name' ),
		    'search_items' =>  'Search '.$upperPlural,
		    'all_items' => 'All '.$upperPlural,
		    'parent_item' => 'Parent '.$upperSingular,
		    'parent_item_colon' => 'Parent '.$upperSingular.':',
		    'edit_item' => 'Edit '.$upperSingular, 
		    'update_item' => 'Update '.$upperSingular,
		    'add_new_item' => 'Add New '.$upperSingular,
		    'new_item_name' => 'New '.$upperSingular.' Name',
		    'menu_name' => $upperSingular,
		);
		
		$capabilities = array(
			'manage_terms' => 'manage_'.$plural,
		    'edit_terms' => 'manage_'.$plural,
		    'delete_terms' => 'manage_'.$plural,
		    'assign_terms' => 'edit_posts',
		);
		
		// hierarchical
		if($hierarchical === true) :
			$hierarchical = array('hierarchical' => true,);
		else :
			$hierarchical = array();
			$specialLabels = array(
			    'popular_items' => 'Popular '.$upperPlural,
			    'separate_items_with_commas' => 'Separate '.$singular.' with commas',
			    'add_or_remove_items' => 'Add or remove '.$singular,
			    'choose_from_most_used' => 'Choose from the most used '.$singular,
			);
			$labels = array_merge($labels, $specialLabels);
		endif;
		// capabilities
		if($cap === true) :
			$cap = array('capabilities' => $capabilities,);
		else :
			$cap = array();
		endif;
		
		$args = array(
		    'labels' => $labels,
		    'show_ui' => true,
		    'rewrite' => array( 'slug' => $singular ),
		);
		
		$args = array_merge($args, $hierarchical, $cap, $settings);
			
		register_taxonomy($singular, $post_type, $args);
	}
}