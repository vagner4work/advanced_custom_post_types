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
 *
 * @return $this
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
    $field .= 'value="'.esc_attr($name).'" />';
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

  $fieldName = $this->get_field_name($name);
  $value = $this->get_field_value($fieldName);

  // value
  if(empty($value)) $value = '';

  $s = $this->get_opts($name, $opts, $fieldName, $label);

  $attr = array(
    'readonly' => $s['read'],
    'class' => "textarea $fieldName {$s['class']}",
    'id' => $s['id'],
    'name' => $s['name'],
    'html' => acpt_sanitize::textarea($value)
  );
  $field = acpt_html::element('textarea', $attr);

  $dev_note = $this->dev_message($fieldName);

  echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['afterField']);

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
  $fieldName = $this->get_field_name($name);

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
  $dev_note = $this->dev_message($fieldName);

  echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['afterField']);

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
  $fieldName = $this->get_field_name($name);

  // name
  $s = $this->get_opts($name, $opts, $fieldName, $label);

  // get options HTML
  if(!empty($options)) :

    $value = $this->get_field_value($fieldName);

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
  $dev_note = $this->dev_message($fieldName);

  echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['afterField']);

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

  $fieldName = $this->get_field_name($name);
  $v = $this->get_field_value($fieldName);
  $s = $this->get_opts($label, array('labelTag' => 'span'), $fieldName, true);

  echo '<div class="control-group">';
  echo $s['label'];
  wp_editor(
      acpt_sanitize::editor($v),
      'wysisyg_'.$fieldName,
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

  /**
   * Get Image Form
   *
   * @param $o
   *
   * @return string
   */
  function get_image_form($o) {
    $value = $this->get_field_value($o['field']);

    if(empty($o['opts']['readonly'])) $o['opts']['readonly'] = true;

    // button text
    $btnValue = $this->get_opt_by_test($o['opts']['button'], "Insert Image", $o['opts']['button']);

    // placeholder image and image id value
    if(!empty($value)) :
      $value = esc_url($value);
      $placeHolderImage = '<img class="upload-img" src="'.esc_url($value).'" />';
      $vID = $this->get_field_value($o['field'].'_id');
    else :
      $vID = $placeHolderImage = '';
    endif;

    $attachmentID = acpt_html::input(array(
      'type' => 'hidden',
      'class' => 'attachment-id-hidden',
      'name' => 'acpt['.$o['field'].'_id]',
      'value' => esc_attr($vID)
    ));

    $btn = array('input' => array(
      'type' => 'button',
      'class' => 'button-primary upload-button',
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
  }

  /**
   * Get Google Map Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_google_map_form($o) {
    $value = esc_attr($this->get_field_value($o['field']));

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

    wp_localize_script('acpt-fields', 'acpt_'.$args['field'].'_color_palette', $args['opts']['palette'] );
    wp_localize_script('acpt-fields', 'acpt_'.$args['field'].'_defaultColor', $args['opts']['default'] );

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
      $valueID = acpt_html::make_html_attr('value', esc_attr($value));
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
    $s = $this->get_opts($o['name'], $o['opts'], $o['field'], $o['label']);
    $v = $this->get_field_value($o['field']);

    $field = acpt_html::input(array(
        'class' => "{$o['classes']}  acpt_{$o['field']} {$s['class']}",
        'type' => 'text',
        'value' => esc_attr($v),
        'name' => $s['name'],
        'id' => $s['id'],
        'readonly' => $s['read']
    ), true);

    $dev_note = $this->dev_message($o['field']);

    return $s['bLabel'].$s['label'].$s['aLabel'].$field.$o['html'].$dev_note.$s['help'].$s['afterField'];
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
   * Testing each field needs to prevent errors.
   *
   * @param $name
   * @param $opts
   * @param $fieldName
   * @param $label
   *
   * @return mixed
   */
  protected function get_opts($name, $opts, $fieldName, $label) {
    $opts = $this->set_empty_keys($opts);

    // help text
    $help = acpt_html::element('p', array(
        'class' => 'help-text',
        'html' => $opts['help']
    ));
    $s['help'] = $this->get_opt_by_test($opts['help'], '', $help);

    // attributes
    $s['class'] = $this->get_opt_by_test($opts['class']);
    $s['read'] = $this->get_opt_by_test($opts['readonly']);
    $s['name'] = $this->get_acpt_post_name($fieldName);
    $s['id'] = 'acpt_'.$fieldName;

    // label
    $s['bLabel'] = $this->get_opt_by_test($opts['bLabel'], BEFORE_LABEL);
    $s['aLabel'] = $this->get_opt_by_test($opts['aLabel'], AFTER_LABEL);
    $s['afterField'] = $this->get_opt_by_test($opts['afterField'], AFTER_FIELD);
    $opts['labelTag'] = $this->get_opt_by_test($opts['labelTag'], 'label');

    if(isset($label)) :
      $s['label'] = acpt_html::element($opts['labelTag'], array(
        'class' => 'control-label',
        'for' => $s['id'],
        'html' => $this->get_opt_by_test($opts['label'], $name)
      ));
    endif;

    return $s;
  }

  /**
   * Get Options By Test
   *
   * Setting the $return field will send those results if the test passes.
   * Default is sent on a failing test.
   *
   * @param $test
   * @param string $default
   * @param bool $return
   *
   * @return bool|string
   */
  private function get_opt_by_test($test, $default = '', $return = true) {
    $return = ($return === true) ? $test : $return;
    return (isset($test)) ? $return : $default;
  }

  /**
   * Set Empty Keys
   *
   * @param $opts
   * @param bool $desired_keys
   *
   * @return mixed
   */
  private function set_empty_keys($opts, $desired_keys = false) {
    $keys = array_keys($opts);

    if($desired_keys === false) {
      $desired_keys = array('readonly', 'button', 'help', 'bLabel', 'aLabel', 'afterField', 'label', 'labelTag', 'class');
    }

    foreach($desired_keys as $desired_key){
      if(in_array($desired_key, $keys)) continue;
      $opts[$desired_key] = null;
    }

    return $opts;
  }

  /**
   * Get $_POST Name
   *
   * This will set the name value for a field
   *
   * @param $field
   * @param string $prefix
   * @param string $suffix
   * @param string $group
   *
   * @return string
   */
  private function get_acpt_post_name($field, $group = '', $prefix = '', $suffix = '' ) {
    return "acpt{$group}[{$suffix}{$field}{$prefix}]";
  }

  private function make_attr_name($field, $group = '', $prefix = '', $suffix = '') {
    $value = $this->get_acpt_post_name($field, $group, $prefix, $suffix);
    return acpt_html::make_html_attr('name', $value);
  }

}

function acpt_form($name, $opts=array()) {
  return new acpt_form($name, $opts);
}