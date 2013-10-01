<?php

$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$optionsList = '';
$opts['labelTag'] = 'span';
$fieldName = $this->get_field_name($name);

// name
$s = $this->get_opts($name, $opts, $fieldName, $label);

// get options HTML
if(!empty($options)) :

  $opts = $this->set_empty_keys($opts, array('group', 'sub'));
  $value = $this->get_field_value($fieldName, $opts['group'], $opts['sub']);

  foreach( $options as $key => $option) :
    if($option == $value)
      $checked = 'checked';
    else
      $checked = null;

    if(array_key_exists('select_key', $opts) && $opts['select_key'] == true)
      true;
    else
      $key = $option;

    $anOption = array(array(
      'label' => array(
        'html' => array(array(
          'input' => array(
            'type' => 'radio',
            'name' => $s['name'],
            'value' => esc_attr($option),
            'checked' => $checked
          )
        ), array(
          'span' => array(
            'html' => $key
          )
        ))
      )
    ));

    $optionsList .= acpt_html::make_html($anOption);

  endforeach;

endif;

$attr = array(
  'readonly' => $s['read'],
  'class' => "radio $fieldName {$s['class']}",
  'id' => $s['id'],
  'html' => $optionsList
);
$field = acpt_html::element('div', $attr);
$dev_note = $this->dev_message($fieldName, $opts['group'], $opts['sub']);

if($this->echo === false) { ob_start(); }
echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['aField']);
if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;