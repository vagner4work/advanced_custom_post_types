<?php

add_action('init', 'makeThem');
function makeThem() {

$args = array(
'supports' => array( 'title', 'editor', 'page-attributes', 'custom'  ),
'hierarchical' => true,
);

$sample = new post_type('sample','samples', false,  $args );

new tax('color', 'colors', $sample, true, false);

}

add_action( 'add_meta_boxes', 'addThem' );

function addThem() {
	new meta_box('custom', array('sample'), array('label' => 'Custom Meta Box'));
}

function meta_custom() {
	$form = new form('details', null);
	$form->text('name', array('label' => 'Text Field'));
	$form->image('image', array('label' => 'Image Field'));
	$form->image('image2', array('label' => 'Image2 Field'));
	$form->file('file', array('label' => 'File Field'));
	$form->textarea('address',array('label' => 'Textarea'));
	$form->select('rooms', array('one', 'two', 'three'), array('label' => 'Select List'));
	$form->radio('baths', array('blue', 'green', 'red'), array('label' => 'Radio Buttons'));
	$form->editor('baths', 'WYSIWYG Editor');
}