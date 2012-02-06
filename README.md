Advanced Custom Post Types
===

This will be a framework for creating not only custom post types in WordPress but it will also give you the ability to rapidly create custom fields.

by Kevin Dees at http://kevindees.cc

Usage
===

Use a php include to add the file acpt.php to your plugin or functions.php theme file.

Making a Custom Post Type
===

	include('acpt/acpt.php');

	add_action('init', 'makethem');
	function makethem() {
		$pt = new post_type();

		$args = array(
			'taxonomies' => array('category', 'post_tag'),
			'supports' => array( 'title', 'editor', 'page-attributes'  ),
			'hierarchical' => true,
		);
		$pt->make('course','courses', false,  $args );
	}

Making a Taxonomy
===

	include('acpt/acpt.php');

	add_action('init', 'makethem');
	function makethem() {
		$tx = new tax();
		$tx->make('color','colors', false );
	}

Together
===

	include('acpt/acpt.php');

	add_action('init', 'makethem');
	function makethem() {
		$tx = new tax();
		$tx->make('color', 'colors', true);

		$pt = new post_type();
		$args = array(
			'taxonomies' => array('color'),
			'supports' => array( 'title', 'editor', 'page-attributes'  ),
			'hierarchical' => true,
		);
		$pt->make('course','courses', false,  $args );
		$pt->make('book','books', false,  $args );
	}