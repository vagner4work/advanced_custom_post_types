<?php
$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$fieldName = $this->get_field_name($name);

$opts = $this->set_empty_keys($opts, array('group', 'sub'));
$value = $this->get_field_value($fieldName, $opts['group'], $opts['sub']);

// value
if(empty($value)) $value = '';

$s = $this->get_opts($name, $opts, $fieldName, $label);

$attr = array(
  'readonly' => $s['read'],
  'class' => "textarea $fieldName {$s['class']}",
  'id' => $s['id'],
  'name' => $s['name'],
  'placeholder' => $s['placeholder'],
  'html' => acpt_sanitize::textarea($value)
);
$field = acpt_html::element('textarea', $attr);

$dev_note = $this->dev_message($fieldName, $opts['group'], $opts['sub']);

if($this->echo === false) { ob_start(); }
echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['aField']);
if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;