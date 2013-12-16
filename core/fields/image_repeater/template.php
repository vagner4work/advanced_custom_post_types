<?php
// button text
$btnValue = $this->get_opt_by_test($o['opts']['button'], "Add Image", $o['opts']['button']);

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
  'class' => 'button upload-button link',
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