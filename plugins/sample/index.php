<?php

add_action('init', 'acpt_sample_init');
function acpt_sample_init() {

  // in supports add the meta box custom
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

add_action( 'add_meta_boxes', 'acpt_meta_init' );

function acpt_meta_init() {
	acpt_meta_box('custom', array('example'), array('label' => 'Custom Meta Box'));
}

function meta_custom() {
  $palette = array('#FF5F20', '#FF751A', '#FF9E57', '#FFC498', '#FFE4D0', '#FFFFFF', '#000000');

	$form = acpt_form('details', array('group' => '[sample]'))
    ->buffer() // start buffer
    ->checkbox('Checkbox', array('desc' => 'Select this for value of 1'))
    ->checkbox('checkbox_2', array('desc' => 'The second checkbox for value of 1'), false)
    ->checkbox('checkbox_3', array('desc' => 'A third box for value of 1'), false)
    ->color('1', array('label' => 'Color Field', 'default' => '#000', 'palette' => $palette))
    ->color('2', array('label' => 'Color Field (no palette)'))
    ->select('Select', array('one', 'two', 'three'))
    ->select('select_key', array('One' => '1', 'Two' => '2'), array('label' => 'Select List (custom key)', 'select_key' =>  true))
    ->radio('radio', array('blue', 'green', 'red'), array('label' => 'Radio Buttons'))
    ->buffer('options')->buffer() // save buffer and start again
    ->text('Text', array('class' => 'example-class', 'help' => 'Example help text'))
    ->text_repeater('Text Repeater', array('class' => 'example-class', 'help' => 'Example help text'))
    ->textarea('Textarea', array('label' => 'Textarea'))
    ->image('Image', array('button' => 'Add Your Image'))
    ->file('File', array('button' => 'Select a File'))
    ->buffer('text')->buffer() // buffer
    ->date('date', array('label' => 'Date Field', 'button' => 'Enter a Date'))
    ->google_map('address', array('label' => 'Address Field'))
    // editors bug out if a metabox is moved, this is a WordPress issue
    ->editor('editor', 'WYSIWYG Editor')
    ->editor('editor_teeny', 'Teeny Editor', array(), array('teeny' => true, 'media_buttons' => false))
    ->buffer('adv'); // save buffer

  $tabs = new acpt_layout();
  $tabs
    ->add_tab( array(
      'id' => 'tab1',
      'title' => "Text &amp; File Fields",
      'content' => $form->buffer['text']
    ) )
    ->add_tab( array(
        'id' => 'tab2',
        'title' => "Option Fields",
        'content' => $form->buffer['options']
      ) )
    ->add_tab( array(
        'id' => 'tab3',
        'title' => "Advanced Fields",
        'content' => $form->buffer['adv']
    ) )
    ->make('metabox');

}