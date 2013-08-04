<?php
add_action('init', 'acpt_slider');
function acpt_slider() {
//include('shortcodes.php');

$args = array(
'supports' => array( 'title', 'page-attributes', 'acpt_slide_options'  ),
'hierarchical' => false,
'public' => false,
'has_archive' => false,
'show_ui' => true
);

$slide = new acpt_post_type('slide','slides', false,  $args );

$slide->icon('refresh');

new acpt_tax('group','groups', $slide, true);
}

add_action( 'add_meta_boxes', 'acpt_slider_meta' );

function acpt_slider_meta() {
	new acpt_meta_box('acpt_slide_options', array('slide'), array('label' => 'Slide'));
}

function meta_acpt_slide_options() {
	$form = new acpt_form('slide', null);
	$form->image('image', array('label' => 'Image URL', 'help' => 'Upload an Image that is 940px by 350px for best results', 'button' => 'Add Your Slide'));
	$form->text('headline', array('label' => 'Headline'));
	$form->textarea('description',array('label' => 'Description'));
	$form->select('showText', array('Yes', 'No'), array('label' => 'Show Headline and Description'));
}

require_once('template.php');