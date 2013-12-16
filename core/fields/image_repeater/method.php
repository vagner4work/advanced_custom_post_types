<?php
$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$field = $this->get_field_name($name);

$args = array(
  'name' => $name,
  'opts' => $opts,
  'classes' => "image-repeater text-repeater",
  'field' => $field,
  'label' => $label,
  'html' => ''
);

if($this->echo === false) { ob_start(); }

$o = $args;

$o['opts'] = $this->set_empty_keys($o['opts']);
$s = $this->get_opts($o['name'], $o['opts'], $o['field'], $o['label']);
$v = $this->get_field_value($o['field'], $o['opts']['group'], '');

$fields = '';

if(count($v) > 0 && is_array($v)) :
  foreach($v as $k => $v ) {

    $input_item = acpt_html::input(array(
      'type' => 'hidden',
      'value' => esc_attr($v),
      'name' => $s['name'].'[]',
      'class' => 'image-id'
    ), true);

    $img = wp_get_attachment_image($v, 'thumbnail');

    $fields = $fields . '<li>'.$input_item.'<div class="image-placeholder">'.$img.'</div><b>Remove</b></li>';
  }
endif;

$hidden_field = acpt_html::input(array(
  'class' => "image-repeater-field",
  'type' => 'hidden',
  'data-name' => $s['name'].'[]'
), true);

$dev_note = $this->dev_message($o['field'], $o['opts']['group'], '');

$the_code = $s['bLabel'].$s['label'].$s['aLabel'].$hidden_field.'<ul class="list image-repeater-set">'.$fields.'</ul>'.$o['html'].$dev_note.$s['help'].$s['aField'];

echo apply_filters($field . '_filter', $the_code);

if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;