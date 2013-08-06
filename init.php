<?php
// Include this file in functions.php or plugin
global $wp_version;
if($wp_version < '3.5' || $wp_version == null ): exit('You need the 3.5+ version of WordPress.');
else: $acpt_version = '3.0.2';
endif;

// load config
require_once('config.php');

// load classes
$lib = array(
  'acpt',
  'html',
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

if($useDepreciated) { require_once('core/depreciated.php'); }

// getting the meta
function acpt_meta($name = '', $fallBack = '', $groups = true, $theID = null) {
    global $post;
    empty($theID) ? $theID = $post->ID : true;

    do_action('start_acpt_meta', $name, $fallBack, $theID);

    // if you are using groups test for nesting
    if($groups) {
      $data = get_post_meta($theID);
      $data = acpt_utility::acpt_get_meta($name, $data);
    } elseif(acpt_validate::bracket($name)) {
      $data = get_post_meta($theID, substr($name, 1, -1), true);
    }

    //if(empty($data)) { $data = get_post_meta($theID, $name, true); }
    empty($data) ? $data = $fallBack : true;
    do_action('end_acpt_meta', $data);
    return $data;
}

function e_acpt_meta($name = '', $fallBack = '', $groups = true, $theID = null) {
    $data = acpt_meta($name, $groups, $fallBack, $theID);
    ($fallBack !== '' ) ? true : $fallBack = 'No string data '.$name;
    is_string($data) ? true : $data = $fallBack;
    echo $data;
}

// setup
if(ACPT_MESSAGES) add_filter('post_updated_messages', 'acpt_utility::set_messages' );
add_action('save_post','acpt_save::save_post_fields');
if(ACPT_STYLES) add_action('admin_init', 'acpt_utility::apply_css');
if( is_admin() ) add_action('admin_enqueue_scripts', 'acpt_utility::upload_scripts');

// load plugins
if(ACPT_LOAD_PLUGINS == true) :
	foreach($acptPlugins as $plugin) {
		$pluginFile = '';
      $pluginsFolder = apply_filters('acpt_plugin_folder', ACPT_FILE_PATH.'/'.ACPT_FOLDER_NAME.'/plugins/');
		if (file_exists($pluginsFolder . $plugin . '/index.php')) {
			$pluginFile = $plugin . '/index.php';
		} else {
			$pluginFile =  $plugin . '.php';
		}
		include_once($pluginsFolder.$pluginFile);
	}
endif;