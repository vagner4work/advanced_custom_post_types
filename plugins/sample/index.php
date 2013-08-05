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
  ->color('color_p', array('label' => 'Color Field', 'default' => '#000', 'palette' => array('#fff', '#f00', '#f30')))
  ->color('color', array('label' => 'Color Field (no palette)'))
  ->image('image', array('label' => 'Image Field', 'button' => 'Add Your Image'))
	->file('file', array('label' => 'File Field', 'button' => 'Select a File'))
	->google_map('address', array('label' => 'Address Field'))
	->date('date', array('label' => 'Date Field', 'button' => 'Enter a Date'))
	->textarea('textarea', array('label' => 'Textarea'))
	->select('select', array('one', 'two', 'three'), array('label' => 'Select List'))
	->select('select_key', array('One' => '1', 'Two' => '2', 'Three' => '3'), array('label' => 'Select List Key', 'select_key' =>  true))
	->radio('radio', array('blue', 'green', 'red'), array('label' => 'Radio Buttons'))
	->editor('editor', 'WYSIWYG Editor');
}