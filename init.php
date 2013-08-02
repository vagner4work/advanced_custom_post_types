<?php
// Include this file in functions.php or plugin
global $wp_version;
if($wp_version < '3.3' || $wp_version == null ): exit('You need the 3.3+ version of WordPress.');
else: $acpt_version = '3.0';
endif;

// load config
require_once('config.php');

// load classes
$lib = array(
  'acpt',
  'save',
  'utility',
  'validate',
  'post_type',
  'tax',
  'role',
  'form',
  'meta_box'
);

foreach($lib as $value) :
  require_once('core/class-'.$value.'.php');
endforeach;

// getting the meta
function acpt_meta($name = '', $fallBack = '', $theID = null) {
    global $post;
    do_action('start_acpt_meta', $name, $fallBack, $theID);
    empty($theID) ? $theID = $post->ID : true;
    $data = get_post_meta($theID, $name, true);
    empty($data) ? $data = $fallBack : true;
    do_action('end_acpt_meta', $data);
    return $data;
}

function e_acpt_meta($name = '', $fallBack = '', $theID = null) {
    $data = acpt_meta($name, $fallBack, $theID);
    is_string($data) ? true : $data = 'Data need to be a string.';
    echo $data;
}

// setup
if(ACPT_MESSAGES) add_filter('post_updated_messages', 'utility::set_messages' );
add_action('save_post','save::save_post_fields');
if(ACPT_STYLES) add_action('admin_init', 'utility::apply_css');
if( is_admin() ) add_action('admin_enqueue_scripts', 'utility::upload_scripts');

// load plugins
if(ACPT_LOAD_PLUGINS == true) :
	foreach($acptPlugins as $plugin) {
		$pluginFile = '';
      $pluginsFolder = ACPT_FILE_PATH.'/'.ACPT_FOLDER_NAME.'/plugins/';
		if (file_exists($pluginsFolder . $plugin . '/index.php')) {
			$pluginFile = $plugin . '/index.php';
		} else {
			$pluginFile =  $plugin . '.php';
		}
		include_once($pluginsFolder.$pluginFile);
	}
endif;