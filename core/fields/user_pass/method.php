<?php
$user_id;
$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$field = $this->get_field_name($name);

if(!array_key_exists('label', $opts)) {
  $opts['label'] = $name;
}

if(array_key_exists('value', $opts)) {
  $v = $opts['value'];
} else {
  $v = '';
}

$opts['group'] = '[user_insert]';

$args = array(
  'name' => 'post_title',
  'opts' => $opts,
  'classes' => "text user-pass",
  'field' => 'user_pass',
  'label' => $label,
  'value' => $v,
  'html' => ''
);

if($this->echo === false) { ob_start(); }
echo apply_filters($field . '_filter', $this->get_pass_form($args));
if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;