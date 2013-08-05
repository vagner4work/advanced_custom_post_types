<?php

add_action('init', 'makeThem');
function makeThem() {

$args_sample = array(
'supports' => array( 'title', 'editor', 'page-attributes', 'custom'  ),
'hierarchical' => true,
);

$args_example = array(
	'supports' => array('title'),
	'public' => false,
	'show_ui' => true
);

$sample = acpt_post_type('sample','samples', false,  $args_sample )->icon('person');
acpt_post_type('example','examples', false,  $args_example )->icon('location');

acpt_tax('color', 'colors', $sample, true, false);

}

add_action( 'add_meta_boxes', 'addThem' );

function addThem() {
	acpt_meta_box('custom', array('sample', 'example'), array('label' => 'Custom Meta Box'));
}

function meta_custom() {
	acpt_form('details')
  ->text('text', array('label' => 'Text Field', 'class' => 'example-class', 'help' => 'Example help text'))
  ->color('1', array('group' => '[details_colors]', 'label' => 'Color Field', 'default' => '#000', 'palette' => array('#fff', '#f00', '#f30')))
  ->color('2', array('group' => '[details_colors]', 'label' => 'Color Field (no palette)'))
  ->image('image', array('label' => 'Image Field', 'button' => 'Add Your Image', 'group' => '[details_files]'))
	->file('file', array('label' => 'File Field', 'button' => 'Select a File', 'group' => '[details_files]'))
	->google_map('address', array('label' => 'Address Field'))
	->date('date', array('label' => 'Date Field', 'button' => 'Enter a Date', 'group' => '[details_adv]'))
	->textarea('textarea', array('label' => 'Textarea', 'group' => '[details_adv]'))
	->select('select', array('one', 'two', 'three'), array('label' => 'Select List', 'group' => '[details_adv]'))
	->select('select_key', array('One' => '1', 'Two' => '2', 'Three' => '3'), array('label' => 'Select List Key', 'select_key' =>  true, 'group' => '[details_adv]'))
	->radio('radio', array('blue', 'green', 'red'), array('label' => 'Radio Buttons', 'group' => '[details_adv]'))
	->editor('editor', 'WYSIWYG Editor', array('group' => '[details_adv]'))
  ->editor('editor_teeny', 'Teeny Editor', array(), array('teeny' => true, 'media_buttons' => false));
}