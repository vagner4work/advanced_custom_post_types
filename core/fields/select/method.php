<?php

$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$optionsList = '';
$fieldName = $this->get_field_name($name);

// get options HTML
if(isset($options)) :

$opts = $this->set_empty_keys($opts, array('group', 'sub'));
$value = $this->get_field_value($fieldName, $opts['group'], $opts['sub']);

foreach( $options as $key => $option) :
if($option == $value)
$selected = 'selected="selected"';
else
$selected = null;

if(array_key_exists('select_key', $opts) && $opts['select_key'] == true)
true;
else
$key = $option;

$option = esc_attr($option);

$optionsList .= "<option $selected value=\"$option\">$key</option>";
endforeach;

endif;

$s = $this->get_opts($name, $opts, $fieldName, $label);

$attr = array(
'readonly' => $s['read'],
'class' => "select $fieldName {$s['class']}",
'id' => $s['id'],
'name' => $s['name'],
'html' => $optionsList
);
$field = acpt_html::element('select', $attr);
$dev_note = $this->dev_message($fieldName, $opts['group'], $opts['sub']);

if($this->echo === false) { ob_start(); }
echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['aField']);
if($this->echo === false) {
$data = ob_get_clean();
$this->buffer['main'] .= $data;
}

return $this;