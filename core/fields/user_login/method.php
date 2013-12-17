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
  $data = get_userdata($user_id);
  $v = $data->user_login;
}

$opts['group'] = '[user_insert]';

$args = array(
  'name' => 'post_title',
  'opts' => $opts,
  'classes' => "text user-login",
  'field' => 'user_login',
  'label' => $label,
  'value' => $v,
  'html' => ''
);

if($this->echo === false) { ob_start(); }
echo apply_filters($field . '_filter', $this->get_email_form($args));
if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;