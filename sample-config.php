<?php
// location of acpt class
define('ACPT_LOCATION', get_stylesheet_directory_uri());
define('ACPT_FILE_PATH', get_stylesheet_directory());
define('ACPT_FOLDER_NAME', 'acpt'); // using another folder path? e.g. inc/acpt

// turn on styles
define('ACPT_STYLES', true);

// dynamic messages for post types
define('ACPT_MESSAGES', true);

// forms settings
define('DEV_MODE', false);

// form html defaults
define('BEFORE_LABEL', '<div class="control-group">');
define('AFTER_LABEL', '<div class="controls">');
define('AFTER_FIELD', '</div></div>');

// load plugins
define('ACPT_LOAD_PLUGINS', true);

// plugins list
$acptPlugins = array('sample', 'seo');

// default color for color picker
$acptDefaultColor = "#fff";

// palettes colors for color picker
$acptPalette = array('#fff', '#000', '#333', '#ccc', '#f00', '#0f0', '#00f');

// use depreciated classes (form, meta_box, post_type, tax, role)
$useDepreciated = true;