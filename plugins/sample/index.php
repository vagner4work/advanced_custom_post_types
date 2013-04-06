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

$sample = new post_type('sample','samples', false,  $args_sample );
$example = new post_type('example','examples', false,  $args_example );

$sample->icon('person');
$example->icon('location');

new tax('color', 'colors', $sample, true, false);

}

add_action( 'add_meta_boxes', 'addThem' );

function addThem() {
	new meta_box('custom', array('sample', 'example'), array('label' => 'Custom Meta Box'));
}

function meta_custom() {
	$form = new form('details', null);
	$form->text('name', array('label' => 'Text Field'));
	$form->image('image', array('label' => 'Image Field', 'button' => 'Add Your Image'));
	$form->file('file', array('label' => 'File Field', 'button' => 'Select a File'));
	$form->google_map('address', array('label' => 'Address Field'));
	$form->date('date', array('label' => 'Date Field', 'button' => 'Enter a Date'));
	$form->textarea('textarea',array('label' => 'Textarea'));
	$form->select('rooms', array('one', 'two', 'three'), array('label' => 'Select List'));
	$form->select('rooms', array('One' => '1', 'Two' => '2', 'Three' => '3'), array('label' => 'Select List Key', 'select_key' =>  true));
	$form->radio('baths', array('blue', 'green', 'red'), array('label' => 'Radio Buttons'));
	$form->editor('baths', 'WYSIWYG Editor');
}