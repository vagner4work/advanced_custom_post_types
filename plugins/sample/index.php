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

$sample = new acpt_post_type('sample','samples', false,  $args_sample );
$example = new acpt_post_type('example','examples', false,  $args_example );

$sample->icon('person');
$example->icon('location');

new acpt_tax('color', 'colors', $sample, true, false);

}

add_action( 'add_meta_boxes', 'addThem' );

function addThem() {
	new acpt_meta_box('custom', array('sample', 'example'), array('label' => 'Custom Meta Box'));
}

function meta_custom() {
	$form = new acpt_form('details', null);
	$form->text('text', array('label' => 'Text Field'));
  $form->color('color_p', array('label' => 'Color Field', 'default' => '#000', 'palette' => array('#ffffff', '#ff0000', '#ff3300')));
  $form->color('color', array('label' => 'Color Field (no palette)'));
  $form->image('image', array('label' => 'Image Field', 'button' => 'Add Your Image'));
	$form->file('file', array('label' => 'File Field', 'button' => 'Select a File'));
	$form->google_map('address', array('label' => 'Address Field'));
	$form->date('date', array('label' => 'Date Field', 'button' => 'Enter a Date'));
	$form->textarea('textarea', array('label' => 'Textarea'));
	$form->select('select', array('one', 'two', 'three'), array('label' => 'Select List'));
	$form->select('select_key', array('One' => '1', 'Two' => '2', 'Three' => '3'), array('label' => 'Select List Key', 'select_key' =>  true));
	$form->radio('radio', array('blue', 'green', 'red'), array('label' => 'Radio Buttons'));
	$form->editor('editor', 'WYSIWYG Editor');
}