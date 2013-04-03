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
		'notebook' => array('a' => '2px -2px', 'i' => '2px -34px'),
		'refresh' => array('a' => '-30px -3px', 'i' => '-30px -35px'),
		'thumbs-up' => array('a' => '-60px -3px', 'i' => '-60px -35px'),
		'box' => array('a' => '-86px -3px', 'i' => '-86px -35px'),
		'bug' => array('a' => '-135px -3px', 'i' => '-135px -35px'),
		'cake' => array('a' => '-159px -3px', 'i' => '-159px -35px'),
		'calendar' => array('a' => '-188px -3px', 'i' => '-188px -8px'),
		'card-biz' => array('a' => '-237px -3px', 'i' => '-237px -10px'),
		'task' => array('a' => '-254px -3px', 'i' => '-254px -35px'),
		'clock' => array('a' => '-286px -3px', 'i' => '-286px -8px'),
		'color' => array('a' => '-310px -3px', 'i' => '-310px -9px'),
		'compass' => array('a' => '-333px -3px', 'i' => '-333px -9px'),
		'dine' => array('a' => '-357px -3px', 'i' => '-357px -8px'),
		'ipad' => array('a' => '-378px -3px', 'i' => '-378px -8px'),
		'ticket' => array('a' => '-399px -3px', 'i' => '-399px -8px'),
		'shirt' => array('a' => '-427px -3px', 'i' => '-427px -9px'),
		'pulse' => array('a' => '-449px -3px', 'i' => '-449px -9px'),
		'card-play' => array('a' => '-471px -3px', 'i' => '-471px -8px'),
		'dine-plate' => array('a' => '-492px -3px', 'i' => '-492px -8px'),
		'pill' => array('a' => '-517px -3px', 'i' => '-517px -10px'),
		'plane' => array('a' => '-538px -3px', 'i' => '-538px -11px'),
		'paint' => array('a' => '-564px -3px', 'i' => '-564px -9px'),
		'mic' => array('a' => '-588px -3px', 'i' => '-588px -8px'),
		'location' => array('a' => '-612px -3px', 'i' => '-612px -8px'),
		'leaf' => array('a' => '-629px -3px', 'i' => '-629px -10px'),
		'music' => array('a' => '-650px -3px', 'i' => '-650px -9px'),
		'wine' => array('a' => '-672px -3px', 'i' => '-672px -8px'),
		'dashboard' => array('a' => '-695px -3px', 'i' => '-695px -11px'),
		'person' => array('a' => '-719px -3px', 'i' => '-719px -9px'),
		'weather' => array('a' => '-742px -3px', 'i' => '-742px -10px')
	);

	function __construct( $singular = null, $plural = null, $cap = false, $settings = array(), $icon = null ) {
		if($singular !== null ) $this->make($singular, $plural, $cap, $settings);
	}

	function icon($name) {
		if(!array_key_exists($name, $this->icon_pos)) exit('Adding Icon: You need to enter a valid icon name. You used ' . $name);

		$this->icon = $name;

		add_action( 'admin_head', array($this, 'set_icon_css') );

	}

	function set_icon_css() { ?>

		<style type="text/css">
			#adminmenu #menu-posts-<?php echo $this->singular; ?> .wp-menu-image {
			  background-image: url('<?php echo ACPT_LOCATION; ?>/<?php echo ACPT_FOLDER_NAME; ?>/core/img/menu.png');
			}

			#adminmenu #menu-posts-<?php echo $this->singular; ?> .wp-menu-image {
			  background-position: <?php echo $this->icon_pos[$this->icon]['i']; ?>;
			}

			#adminmenu #menu-posts-<?php echo $this->singular; ?>:hover div.wp-menu-image,
			#adminmenu #menu-posts-<?php echo $this->singular; ?>.wp-has-current-submenu div.wp-menu-image,
			#adminmenu #menu-posts-<?php echo $this->singular; ?>.current div.wp-menu-image {
			  background-position: <?php echo $this->icon_pos[$this->icon]['a']; ?>;
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