<?php

$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$field = $this->get_field_name($name);

$args = array(
  'name' => $name,
  'opts' => $opts,
  'classes' => "text",
  'field' => $field,
  'label' => $label,
  'value' => $opts['value'],
  'html' => ''
);

if($this->echo === false) { ob_start(); }
echo apply_filters($field . '_filter', $this->get_hidden_form($args));
if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;