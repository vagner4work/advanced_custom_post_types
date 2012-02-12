Advanced Custom Post Types
===

This is a framework for creating not only custom post types, roles and taxonomies in WordPress but it will also give you the ability to rapidly create custom fields (post types only).

by Kevin Dees at http://kevindees.cc
or on twitter https://twitter.com/kevindees

Contributors: Gina Guerrero https://twitter.com/mnemyx

For custom fields try http://wordpress.org/extend/plugins/advanced-custom-fields if you have issues with the advanced parts of ACTP.

Usage
===

Use a php include to add the file acpt.php to your plugin or functions.php theme file. For more advanced users look at the code comments for help on what args are avalible.

Troubleshooting
===

Slug
---

If your slugs are not working be sure you have flushed the permalink rules. To do this go to the permalinks and save the settings. No need to mod the .htaccess file if told.

Making a Custom Post Type
===

Advanced Users See: post_type.php

Making post types with ACPT is fast and easy. The post_type class takes up to 4 arguments (only the first two are required). First the singular name and then the plural name of your post type (makes these lowercase). The next is for capabilities. If you don’t know how capabilities work set this to false and everything should work expected (the default, false, is the same as posts capabilities). Set capabilities to true to create custom capabilities using the post types name (see roles for advanced usage). Last, you have the settings argument. This is used if you want to change the default settings or override them. Use the settings argument the same as you would for creating post types using Wordpress building registration method.

Code Example
---

	include('acpt/acpt.php');

	add_action('init', 'makethem');
	function makethem() {
		$pt = new post_type();

		$args = array(
			'taxonomies' => array('category', 'post_tag'),
			'supports' => array( 'title', 'editor', 'page-attributes'  ),
			'hierarchical' => true,
		);
		$pt->make('book','books', false,  $args );
	}

Making a Taxonomy
===

Advanced Users See: tax.php

Making taxonomies with ACPT is fast and easy. The tax class takes up to 6 arguments (only the first 2 are required). First the singular name and then the plural name of your taxonomy (makes these lowercase). Third, you have hierarchy. Set hierarchy to true if you want to allow the taxonomy to have descendants (the default, false). Forth, is for the singular name of the post type you want the taxonomy to be used for (you can also set this in the post type itself, I recommend this way). The fifth is for capabilities. If you don’t know how capabilities work set this to false and everything should work expected (the default, false). Set capabilities to true to create custom capabilities using the taxonomies name (see roles for advanced usage). Last, you have the settings argument. This is used if you want to change the default settings or override them. Use the settings argument the same as you would for registering taxonomies using Wordpress building registration method.

Code Example
---

	include('acpt/acpt.php');

	add_action('init', 'makethem');
	function makethem() {
		$tx = new tax();
		$tx->make('color','colors', false );
	}

Roles
===

Advanced Users See: role.php

Roles are the most powerful part of ACPT. You can make(), update() and remove() with the role class. When working with roles in ACPT you need to understand how roles work in Wordpress to keep your site installation working smoothly. Unlike Taxonomies and Post Types, when roles are made they are added to the DB. This means you only need to run role code once for it to work. It is best to run this code on theme switching or plugin activation. You can get away with running the code once other ways but this is a common way to do so without a UI.

For basic usage be sure you know how switching themes and activating plugins work.

Themes: http://www.krishnakantsharma.com/2011/01/activationdeactivation-hook-for-wordpress-theme/
Plugins: http://codex.wordpress.org/Function_Reference/register_activation_hook

WARNING: You should not work with roles unless you know what you are doing. Also, Be sure you consider a plan of attack for when your theme or plugin is removed or deactivated. Using roles is ment for advanced users only.

Make Arguments
---

You can set the first argument with capital letters. Formatted name is suggested.


Bad Code Example
---

	include('acpt/acpt.php');
	add_action('init', 'makethem');
	function makethem() {
		$r = new role();
		$r->make('Library Manager', array('read'), array('book', 'books'));
		$r->update('Administrator', null, null, array('book','books'));
	}

Together: Post Type and Taxonomy
===

Here is an example of how to work with Post Types and Taxonomies together.

Code Example
---

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
		$pt->make('book','books', false,  $args );
		$pt->make('course','courses', false,  $args );
	}

Together: Post Type, Meta Box, Form and Taxonomy
===

This is still not fully tested and needs a lot of security work. Use at your own risk.

Code
---

	include('acpt/acpt.php');
	add_action('admin_init', 'meta_boxes');
	function meta_boxes() {
		$m = new meta_box();
		$m->make('New box');
		$m->make('Details');
	}

	add_action('init', 'makethem');
	function makethem() {
		$p = new post_type();
		$t = new tax();
		$t->make('color','colors', false);
		$args = array(
	        'taxonomies' => array('color'),
	        'supports' => array( 'title', 'editor', 'page-attributes', 'new_box', 'details' ),
	        'hierarchical' => true,
	    );
	    $p->make('book', 'books', false, $args);
	}

	function new_box() {
		$f = new form();
		$f->make('new');
		$f->input('name');
		$f->end();
	}

	function details() {
		$f = new form();
		$f->make('details');
		$f->editor('textbox');
		$f->end();
	}