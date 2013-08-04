<?php

class acpt_form extends acpt {

public $name = null;
public $action = null;
public $method = null;

function __construct($name, $opts=array()) {
  return $this->make($name, $opts);
}

/**
 * Make Form.
 *
 * @param string $name singular name is required
 * @param array $opts args [action, method]
 */
function make($name, $opts=array()) {
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    if(isset($opts['method'])) :
        $this->method = $opts['method'];
        $field = '<form id="'.$name.'" ';
        $field .= isset($opts['method']) ? 'method="'.$opts['method'].'" ' : 'method="post" ';
        $field .= isset($opts['action']) ? 'action="'.$opts['action'].'" ' : 'action="'.$name.'" ';
        $field .= '>';
    endif;

    if(isset($opts['action'])) $this->method = $opts['action'];

    $this->name = $name;

    if(isset($field)) echo $field;
    wp_nonce_field('nonce_actp_nonce_action','nonce_acpt_nonce_field');
    echo '<input type="hidden" name="save_acpt" value="true" />';

    return $this;
}

/**
 * End Form.
 *
 * @param string $name singular name is required
 * @param array $opts args override and extend
 */
function end($name=null, $opts=array()) {
  if($name) :
    $field = $opts['type'] == 'button' ? '<input type="button"' : '<input type="submit"';
    $field .= 'value="'.$name.'" />';
    $field .= '</form>';
  endif;

  if(isset($field)) echo $field;
}

/**
 * Form Text.
 *
 * @param string $name singular name is required
 * @param array $opts args override and extend
 * @param bool $label show label or not
 * @return $this
 */
function text($name, $opts=array(), $label = true) {
  $this->test_for($this->name, 'Making Form: You need to make the form first.');
  $this->test_for($name, 'Making Form: You need to enter a singular name.');

  $field = $this->get_field_name($name);

  $args = array(
    'name' => $name,
    'opts' => $opts,
    'classes' => "text",
    'field' => $field,
    'label' => $label,
    'html' => ''
  );

  echo apply_filters($field . '_filter', $this->get_text_form($args));

  return $this;
}

/**
 * Form URL.
 *
 * @param string $name singular name is required
 * @param array $opts args override and extend
 * @param bool $label show label or not
 */
function url($name, $opts=array(), $label = true) {
  $this->text($name, $opts, $label);
}

/**
 * Form Color
 *
 * this function works well for making a form element {@link get_color_form()}
 *
 * @param string $name singular name is required
 * @param array $opts args override and extend
 * @param bool $label show label or not
 * @return $this
 */
function color($name, $opts=array(), $label = true) {
  $this->test_for($this->name, 'Making Form: You need to make the form first.');
  $this->test_for($name, 'Making Form: You need to enter a singular name.');

  $fieldName = $this->get_field_name($name);

  $args = array(
    'name' => $name,
    'opts' => $opts,
    'classes' => "color color-picker",
    'field' => $fieldName,
    'label' => $label,
    'html' => ''
  );

  echo apply_filters($fieldName . '_filter', $this->get_color_form($args));

  return $this;
}

/**
 * Form Textarea.
 *
 * @param string $name singular name is required
 * @param array $opts args override and extend
 * @param bool $label show label or not
 * @return $this
 */
function textarea($name, $opts=array(), $label = true) {
  $this->test_for($this->name, 'Making Form: You need to make the form first.');
  $this->test_for($name, 'Making Form: You need to enter a singular name.');

  $fieldName = $this->get_field_name($name, $opts, 'textarea');
  $value = $this->get_field_value($fieldName);

  // value
  if(empty($value)) $value = '';

  $s = $this->get_opts($name, $opts, $fieldName, $label);

  $field = "<textarea class=\"textarea $fieldName {$s['class']}\" {$s['id']} {$s['size']} {$s['readonly']} {$s['nameAttr']} />$value</textarea>";
  $dev_note = $this->dev_message($fieldName);

  echo apply_filters($fieldName . '_filter', $s['beforeLabel'].$s['label'].$s['afterLabel'].$field.$dev_note.$s['help'].$s['afterField']);

  return $this;
}

/**
 * Form Select.
 *
 * @param string $name singular name is required
 * @param array $options values for select options
 * @param array $opts args override and extend
 * @param bool $label show label or not
 * @return $this
 */
function select($name, $options=array('Key' => 'Value'), $opts=array(), $label = true) {
  $this->test_for($this->name, 'Making Form: You need to make the form first.');
  $this->test_for($name, 'Making Form: You need to enter a singular name.');

  $optionsList = '';
  $fieldName = $this->get_field_name($name, 'select');

  // get options HTML
  if(isset($options)) :

    $value = $this->get_field_value($fieldName);

    foreach( $options as $key => $option) :
      if($option == $value)
        $selected = 'selected="selected"';
      else
        $selected = null;

      if(array_key_exists('select_key', $opts) && $opts['select_key'] == true)
        true;
      else
        $key = $option;

      $optionsList .= "<option $selected value=\"$option\">$key</option>";
    endforeach;

  endif;

  $s = $this->get_opts($name, $opts, $fieldName, $label);

  $field = "<select class=\"select $fieldName {$s['class']}\" {$s['id']} {$s['size']} {$s['readonly']} {$s['nameAttr']} />$optionsList</select>";
  $dev_note = $this->dev_message($fieldName);

  echo apply_filters($fieldName . '_filter', $s['beforeLabel'].$s['label'].$s['afterLabel'].$field.$dev_note.$s['help'].$s['afterField']);

  return $this;
}

/**
 * Form Radio.
 *
 * @param string $name singular name is required
 * @param array $options values for radio options
 * @param array $opts args override and extend
 * @param bool $label show label or not
 * @return $this
 */
function radio($name, $options=array('Key' => 'Value'), $opts=array(), $label = true) {
  $this->test_for($this->name, 'Making Form: You need to make the form first.');
  $this->test_for($name, 'Making Form: You need to enter a singular name.');

  $optionsList = '';
  $opts['labelTag'] = 'span';
  $fieldName = $this->get_field_name($name, 'radio');

  // name
  $nameAttr = 'name="acpt['.$fieldName.']"';

  // get options HTML
  if(!empty($options)) :

    $value = $this->get_field_value($fieldName);

    foreach( $options as $key => $option) :
      if($option == $value)
        $checked = 'checked="checked"';
      else
        $checked = null;

      if(array_key_exists('select_key', $opts) && $opts['select_key'] == true)
        true;
      else
        $key = $option;

      $optionsList .= "<label><input type=\"radio\" $nameAttr $checked value=\"$option\" /><span>$key</span></label>";

    endforeach;

  endif;

  $s = $this->get_opts($name, $opts, $fieldName, $label);

  $field = "<div class=\"radio $fieldName {$s['class']}\" {$s['id']} />$optionsList</div>";
  $dev_note = $this->dev_message($fieldName);

  echo apply_filters($fieldName . '_filter', $s['beforeLabel'].$s['label'].$s['afterLabel'].$field.$dev_note.$s['help'].$s['afterField']);

  return $this;
}

/**
 * Form WP Editor.
 *
 * @param string $name singular name is required
 * @param bool $label text for the label
 * @param array $opts args override and extend wp_editor
 * @return $this
 */
function editor($name, $label=null, $opts=array()) {
  $this->test_for($this->name, 'Making Form: You need to make the form first.');
  $this->test_for($name, 'Making Form: You need to enter a singular name.');

  $fieldName = $this->get_field_name($name, 'editor');
  $value = $this->get_field_value($fieldName);

  if(empty($value)) $value = '';

  $s = $this->get_opts($label, array('labelTag' => 'span'), $fieldName, true);

  echo '<div class="control-group">';
  echo $s['label'];
  wp_editor(
      $value,
      'wysisyg_'.$this->name.'_'.$name,
      array_merge($opts,array('textarea_name' => $this->get_acpt_post_name($fieldName)))
  );
  echo $this->dev_message($fieldName);
  echo '</div>';

  return $this;
}

/**
 * Form Image
 *
 * @param string $name singular name is required
 * @param array $opts args override and extend
 * @param bool $label show label or not
 * @return $this
 */
function image($name, $opts=array(), $label = true) {
  $this->test_for($this->name, 'Making Form: You need to make the form first.');
  $this->test_for($name, 'Making Form: You need to enter a singular name.');

  $fieldName = $this->get_field_name($name);

  $args = array(
    'name' => $name,
    'opts' => $opts,
    'classes' => "image upload-url",
    'field' => $fieldName,
    'label' => $label,
    'html' => ''
  );

  echo apply_filters($fieldName . '_filter', $this->get_image_form($args));

  return $this;
}

  /**
   * Form File
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function file($name, $opts=array(), $label = true) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "file upload-url",
      'field' => $fieldName,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($fieldName . '_filter', $this->get_file_form($args));

    return $this;
  }

  /**
   * Google Maps.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function google_map($name, $opts=array(), $label = true) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "googleMap",
      'field' => $fieldName,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($fieldName . '_filter', $this->get_google_map_form($args));

    return $this;
  }

  /**
   * Date.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function date($name, $opts=array(), $label = true) {
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

    echo apply_filters($fieldName . '_filter', $this->get_text_form($args));

    return $this;
  }

  function get_image_form($o) {
    $value = $this->get_field_value($o['field']);

    if(empty($o['opts']['readonly'])) $o['opts']['readonly'] = true;

    // button text
    if(isset($opts['button'])) :
      $btnValue = $o['opts']['button'];
    else :
      $btnValue = "Insert Image";
    endif;

    // placeholder image and image id value
    if(!empty($value)) :
      $value = esc_url($value);
      $placeHolderImage = '<img class="upload-img" src="'.$value.'" />';
      $vID = $this->get_field_value($o['field'].'_id');
    else :
      $vID = $placeHolderImage = '';
    endif;

    $attachmentID = acpt_html::make_input(array(
      'type' => 'hidden',
      'class' => 'attachment-id-hidden',
      'name' => 'acpt['.$o['field'].'_id]',
      'value' => $vID
    ));

    $btn = array('input' => array(
      'type' => 'button',
      'class' => 'button-primary upload-button',
      'value' => $btnValue
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
  }

  /**
   * Get Google Map Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_google_map_form($o) {
    $value = $this->get_field_value($o['field']);

    // set http
    if (is_ssl()) $http = 'https://';
    else $http = 'http://';

    // zoom
    if(empty($value)) $zoom = 1;
    else $zoom = 15;

    $attrName = $this->make_attr_name($o['field'], '_encoded');

    $o['html'] = "<input type=\"hidden\" class=\"googleMap-encoded\" value=\"{$value}\" {$attrName} />";
    $o['html'] .= '<p class="map"><img src="'.$http.'maps.googleapis.com/maps/api/staticmap?center='.$value.'&zoom='.$zoom.'&size=1200x140&sensor=true&markers='.$value.'" class="map-image" alt="Map Image" /></p>';

    return $this->get_text_form($o);
  }


  /**
   * Get Color Form
   *
   * get color input and form data
   *
   * @param $args
   *
   * @return string
   */
  protected function get_color_form($args) {
    global $acptPalette, $acptDefaultColor;

    if(!isset($args['opts']['palette'])) {
      $args['opts']['palette'] = $acptPalette;
    }

    if(!isset($args['opts']['default'])) {
      $args['opts']['default'] = $acptDefaultColor;
    }

    wp_localize_script('fields', 'acpt_'.$args['field'].'_color_palette', $args['opts']['palette'] );
    wp_localize_script('fields', 'acpt_'.$args['field'].'_defaultColor', $args['opts']['default'] );

    return $this->get_text_form($args);
  }

  /**
   * Get File Form
   *
   * @param $o
   *
   * @return string
   */
  function get_file_form($o) {

    $value = $this->get_field_value($o['field']."_id");

    if(empty($o['opts']['readonly'])) $o['opts']['readonly'] = true;

    // button
    if(isset($o['opts']['button'])) :
      $button = $o['opts']['button'];
    else :
      $button = "Insert File";
    endif;

    // placeholder image and image id value
    if(isset($value)) :
      $valueID = acpt_html::make_html_attr('value', $value);
    else :
      $valueID = '';
    endif;

    $attrName = $this->make_attr_name($o['field'], '_id');

    $o['html'] = "<input type=\"hidden\" class=\"attachment-id-hidden\" {$attrName} {$valueID}>";
    $o['html'] .= '<input type="button" class="button-primary upload-button" value="'.$button.'"> <span class="clear-attachment">clear file</span>';

    return $this->get_text_form($o);
  }

  /**
   * Get Text Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_text_form($o) {
    // setup
    $s = $this->get_opts($o['name'], $o['opts'], $o['field'], $o['label']);

    // value
    $v = $this->get_field_value($o['field']);
    $v = acpt_html::make_html_attr('value', $v);

    $classes = acpt_html::make_html_attr('class', $o['classes'] . '  acpt_' . $o['field'] . ' ' . $s['class']);

    $field = "<input type=\"text\" {$classes} {$s['attr']} $v />";
    $dev_note = $this->dev_message($o['field']);

    return $s['beforeLabel'].$s['label'].$s['afterLabel'].$field.$o['html'].$dev_note.$s['help'].$s['afterField'];
  }

  /**
   * Get Field Value
   *
   * Get the value if it is a post type or another page form
   *
   * @param $field
   *
   * @return mixed|null|string
   */
  protected function get_field_value($field) {
    global $post;

    if(isset($post->ID)) { $value = acpt_meta($field); }
    else { $value = null; }

    return $value;
  }

  /**
   * Get Dev Note
   *
   * Add the dev field to the admin to see the a acpt_meta() function
   *
   * @param $fieldName
   *
   * @return string
   */
  protected function dev_message($fieldName) {
    if(DEV_MODE == true) return "<input class=\"dev_note\" readonly value=\"acpt_meta('{$fieldName}');\" />";
    else return '';
  }

  /**
   * Get Field Name
   *
   * @param $name
   *
   * @return string
   */
  protected function get_field_name($name) {
    return $this->name.'_'.$name;
  }

  /**
   * Get Input Options
   *
   * @param $name
   * @param $opts
   * @param $fieldName
   * @param $label
   *
   * @return mixed
   */
  protected function get_opts($name, $opts, $fieldName, $label) {

    $s['attr'] = '';

    // classes
    if ( is_string(isset($opts['class'])) ) $s['class'] = $opts['class'];
    else $s['class'] = '';

    // readonly
    if ( isset($opts['readonly']) ) {
      $s['readonly'] = 'readonly="readonly"';
      $s['attr'] .= ' '. $s['readonly'];
    } else {
      $s['readonly'] = '';
    }

    // size
    if ( array_key_exists('size', $opts) && is_integer($opts['size']) ) {
      $s['size'] = 'size="'.$opts['size'].'"';
      $s['attr'] .= ' '. $s['size'];
    } else {
      $s['size'] = '';
    }

    // name and id
    $s['nameAttr'] = $this->make_attr_name($fieldName);
    $s['attr'] .= ' '. $s['nameAttr'];

    $id = 'acpt_'.$fieldName;

    $s['id'] = 'id="'.$id.'"';
    $s['attr'] .= ' '. $s['id'];

    // help text
    if(isset($opts['help'])) :
      $s['help'] = '<p class="help-text">'.$opts['help'].'</p>';
    else :
      $s['help'] = '';
    endif;

    // beforeLabel
    if(isset($opts['beforeLabel'])) :
      $s['beforeLabel'] = $opts['beforeLabel'];
    else :
      $s['beforeLabel'] = BEFORE_LABEL;
    endif;

    // afterLabel
    if(isset($opts['afterLabel'])) :
      $s['afterLabel'] = $opts['afterLabel'];
    else :
      $s['afterLabel'] = AFTER_LABEL;
    endif;

    // afterField
    if(isset($opts['afterField'])) :
      $s['afterField'] = $opts['afterField'];
    else :
      $s['afterField'] = AFTER_FIELD;
    endif;

    // label
    if(empty($opts['labelTag'])) $opts['labelTag'] = 'label';

    if(isset($label)) :
      $labelName = (isset($opts['label']) ? $opts['label'] : $name);

      $s['label'] = acpt_html::make_html(array(array(
          $opts['labelTag'] => array(
            'class' => 'control-label',
            'for' => $id,
            'html' => $labelName
          )
      )));

    endif;

    return $s;
  }

  private function make_attr_name($field, $prefix = '', $suffix = '', $group = '') {
    $value = $this->get_acpt_post_name($field, $prefix, $suffix, $group);
    return acpt_html::make_html_attr('name', $value);
  }

  private function get_acpt_post_name($field, $prefix = '', $suffix = '', $group = '') {
    return "acpt{$group}[{$suffix}{$field}{$prefix}]";
  }

}

function acpt_form($name, $opts=array()) {
  return new acpt_form($name, $opts);
}