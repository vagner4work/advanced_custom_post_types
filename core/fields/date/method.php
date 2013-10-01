<?php
$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$fieldName = $this->get_field_name($name);

$args = array(
  'name' => $name,
  'opts' => $opts,
  'classes' => "date date-picker",
  'field' => $fieldName,
  'label' => $label,
  'html' => ''
);

if($this->echo === false) { ob_start(); }

$args['opts'] = $this->set_empty_keys($args['opts']);
//$o['opts']['readonly'] = $this->get_opt_by_test($o['opts']['readonly'], true);

$date_form = $this->get_text_form($args);

echo apply_filters($fieldName . '_filter', $date_form);
if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;