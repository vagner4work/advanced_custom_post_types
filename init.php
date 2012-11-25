<?php
// Include this file in functions.php or plugin
global $wp_version;
if($wp_version < '3.3' || $wp_version == null ): exit('You need the 3.3+ version of WordPress.');
else: $acpt_version = '2.0';
endif;

// load config
require_once('config.php');

// load classes
require_once('core/acpt.php');
include('core/post_type.php');
include('core/tax.php');
include('core/role.php');
include('core/form.php');
include('core/meta_box.php');

// setup
if(ACPT_MESSAGES) add_filter('post_updated_messages', 'acpt::set_messages' );
add_action('save_post','acpt::save_form');
if(ACPT_STYLES) add_action('admin_init', 'acpt::apply_css');
if( is_admin() ) add_action('admin_enqueue_scripts', 'acpt::upload_scripts');

// load plugins
if(ACPT_LOAD_PLUGINS == true) :
	foreach($acptPlugins as $plugin) {
		$pluginFile = '';
		if (file_exists(ACPT_FILE_PATH . '/acpt/plugins/' . $plugin . '/index.php')) {
			$pluginFile = 'plugins/' . $plugin . '/index.php';
		} else {
			$pluginFile = 'plugins/' . $plugin . '.php';
		}
		include_once($pluginFile);
	}
endif;