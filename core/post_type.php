<?php
/**
  * Post Type
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
class post_type extends acpt {

	public $singular = null;
	public $plural = null;
	public $icon = null;
	public $icon_pos = array(
		'notebook' => array('a' => '-7px -8px'),
		'refresh' => array('a' => '-37px -9px'),
		'thumbs-up' => array('a' => '-67px -8px'),
		'box' => array('a' => '-93px -9px'),
		'bug' => array('a' => '-144px -10px'),
		'cake' => array('a' => '-167px -11px'),
		'calendar' => array('a' => '-188px -8px'),
		'card-biz' => array('a' => '-237px -10px'),
		'task' => array('a' => '-262px -8px'),
		'clock' => array('a' => '-286px -8px'),
		'color' => array('a' => '-310px -9px'),
		'compass' => array('a' => '-333px -9px'),
		'dine' => array('a' => '-357px -8px'),
		'ipad' => array('a' => '-378px -8px'),
		'ticket' => array('a' => '-399px -8px'),
		'shirt' => array('a' => '-427px -9px'),
		'pulse' => array('a' => '-449px -9px'),
		'card-play' => array('a' => '-471px -8px'),
		'dine-plate' => array('a' => '-492px -8px'),
		'pill' => array('a' => '-517px -10px'),
		'plane' => array('a' => '-538px -11px'),
		'paint' => array('a' => '-564px -9px'),
		'mic' => array('a' => '-588px -8px'),
		'location' => array('a' => '-612px -8px'),
		'leaf' => array('a' => '-629px -10px'),
		'music' => array('a' => '-650px -9px'),
		'wine' => array('a' => '-672px -8px'),
		'dashboard' => array('a' => '-695px -11px'),
		'person' => array('a' => '-719px -9px'),
		'weather' => array('a' => '-742px -10px')
	);

	function __construct( $singular = null, $plural = null, $cap = false, $settings = array(), $icon = null ) {
		if($singular !== null ) $this->make($singular, $plural, $cap, $settings);
	}

	function icon($name) {
		if(!$name) exit('Adding Icon: You need to enter an icon name.');

		$this->icon = $name;

		add_action( 'admin_head', 'set_icon_css');

	}

	function set_icon_css() { ?>

		<style type="text/css">
			#adminmenu #menu-posts-<?php echo $this->singular; ?> .wp-menu-image {
			  background-image: url(../img/menu.png);
			}

			#adminmenu #menu-posts-<?php echo $this->singular; ?> .wp-menu-image {
			  background-position: -29px -33px;
			}

			#adminmenu #menu-posts-<?php echo $this->singular; ?>:hover div.wp-menu-image,
			#adminmenu #menu-posts-<?php echo $this->singular; ?>.wp-has-current-submenu div.wp-menu-image,
			#adminmenu #menu-posts-<?php echo $this->singular; ?>.current div.wp-menu-image {
			  background-position: <?php echo $icon_pos[$this->icon][a]; ?>;
			}
		</style>

	<?php }

	/**
	 * Make Post Type. Do not use before init.
	 *
	 * @param string $singular singular name is required
	 * @param string $plural plural name is required
	 * @param boolean $cap turn on custom capabilities
	 * @param array $settings args override and extend
	 */
	function make($singular = null, $plural = null, $cap = false, $settings = array() ) {
		if(!$singular) exit('Making Post Type: You need to enter a singular name.');
		if(!$plural) exit('Making Post Type: You need to enter a plural name.');

		// make lowercase
		$singular = strtolower($singular);
		$plural = strtolower($plural);

		// setup object for later use
		$this->plural = $plural;
		$this->singular = $singular;

		// make uppercase
		$upperSingular = ucwords($singular);
		$upperPlural = ucwords($plural);

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

		if($cap === true) :
			$cap = array(
				'capability_type' => $singular,
				'capabilities' => $capabilities,
			);
		else :
			$cap = array();
		endif;

		$args = array(
			'labels' => $labels,
			'description' => $plural,
			'rewrite' => array( 'slug' => sanitize_title($plural)),
			'public' => true,
			'has_archive' => true,
		);

		$args = array_merge($args, $cap, $settings);

		// Register post type
		register_post_type($singular, $args);
	}
}