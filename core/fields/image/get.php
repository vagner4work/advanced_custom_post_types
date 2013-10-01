<?php
$o['opts'] = $this->set_empty_keys($o['opts']);
$o['opts']['readonly'] = $this->get_opt_by_test($o['opts']['readonly'], true);

// setup for grouping
$group = $this->get_opt_by_test($o['opts']['group'], '');
$sub = $this->get_opt_by_test($o['opts']['sub'], '');
$field = $o['field'];

$value = $this->get_field_value($field, $group, $sub);
$name = $this->get_acpt_post_name($field.'_id', $group, $sub);

// button text
$btnValue = $this->get_opt_by_test($o['opts']['button'], "Insert Image", $o['opts']['button']);

// placeholder image and image id value
if(!empty($value)) :
  $placeHolderImage = '<img class="upload-img" src="'.esc_url($value).'" />';
  $vID = $this->get_field_value($field.'_id', $group, $sub);
else :
  $vID = $placeHolderImage = '';
endif;

$attachmentID = acpt_html::input(array(
  'type' => 'hidden',
  'class' => 'attachment-id-hidden',
  'name' => $name,
  'value' => esc_attr($vID)
));

$btn = array('input' => array(
  'type' => 'button',
  'class' => 'button upload-button',
  'value' => esc_attr($btnValue)
));

$phRemove = array(
  'a' => array(
    'class' => 'remove-image',
    'html' => 'remove'
  )
);

$phImg = array(
  'none' => array(
    'html' => $placeHolderImage
  )
);

$ph = array(
  'div' => array(
    'class' => 'image-placeholder',
    'html' => array(
      $phRemove,
      $phImg
    )
  )
);

$html = array($attachmentID, $btn, $ph );

$o['html'] = acpt_html::make_html($html);

return $this->get_text_form($o);