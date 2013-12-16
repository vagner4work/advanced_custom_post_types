<?php
global $post;
$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$field = $this->get_field_name($name);

if(!array_key_exists('label', $opts)) {
  $opts['label'] = $name;
}

if(array_key_exists('value', $opts)) {
  $v = $opts['value'];
} else {
  $v = get_the_title($post->ID);
}

$opts['group'] = '[insert]';

$args = array(
  'name' => 'post_title',
  'opts' => $opts,
  'classes' => "text title",
  'field' => 'post_title',
  'label' => $label,
  'value' => $v,
  'html' => ''
);

if($this->echo === false) { ob_start(); }
echo apply_filters($field . '_filter', $this->get_title_form($args));
if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;