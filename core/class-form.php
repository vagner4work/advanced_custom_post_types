<?php

class acpt_form extends acpt {

  public $name = null;
  public $action = null;
  public $method = null;
  public $group = null;
  public $label = null;
  public $labelTag = null;
  public $bLabel = null;
  public $aLabel = null;
  public $aField = null;
  public $echo = null;
  public $buffer = array('main' => '');
  private $buffering = false;
  public $saveMessage = 'Changes Saved';
  private $saveName = null;
  public $store = null;
  public $user = null;
  public $connect = null;
  public $insert = false;
  public $insert_data = array();
  public $added_post_id = null;

  function __construct($name, $opts=array(), $echo = true, $store = null) {
    return $this->make($name, $opts, $echo, $store);
  }

  /**
   * Make Form.
   *
   * @param string $name singular name is required
   * @param array $opts args [action, method]
   * @param bool $echo
   *
   * @return $this
   */
  function make($name, $opts=array(), $echo = true, $store = null) {
    $this->test_for($name, 'Making Form: You need to enter a singular name.');
    $this->name = $name;

    if(isset($store) && is_string($store)) {
      $this->store = $store;
    } elseif(isset($store) && is_array($store)) {
      $this->store = $store['store'];
      $this->connect = $store['connect'];
      $this->insert = $store['insert'];
      $this->insert_data = $store['insert_data'];
    } elseif(is_int($store)) {
      $this->store = $store;
    }


    $opts = $this->set_empty_keys($opts, array('group', 'label', 'labelTag', 'bLabel', 'aLabel', 'aField', 'method', 'action'));
    $this->group = $this->get_opt_by_test($opts['group'], '');

    if(is_bool($echo)) {
      $this->echo = $echo;
    } else {
      $this->echo = true;
    }

    if($opts['label'] === false ) {
      $this->label = false;
    } elseif($opts['label'] === true) {
      $this->label = true;
    }

    $this->labelTag = $this->get_opt_by_test($opts['labelTag']);
    $this->bLabel = is_string($opts['bLabel']) ? $opts['bLabel'] : null;
    $this->aLabel = is_string($opts['aLabel']) ? $opts['aLabel'] : null;
    $this->aField = is_string($opts['aField']) ? $opts['aField'] : null;
    $this->saveName = 'save_acpt_form_'.$this->name;

    if($opts['method'] === true ) {

      if(isset($_POST[$this->saveName])) {
        if(empty($this->store)) {
          $this->store = 'options';
        }

        // if editing or adding a post
        if($this->insert == true) {
          if( is_int($this->store) ) {
            $args = array( 'ID' => $this->store);

            if(!empty($_POST['acpt']['insert']['post_title']))  {
              $args['post_title'] = $_POST['acpt']['insert']['post_title'];
            }

           wp_update_post( $args );
          } else {
           $args = array(
             'post_type' => $this->insert_data['post_type'],
             'post_status'   => 'publish',
             'post_title'   => 'item'
           );

            if(!empty($_POST['acpt']['insert']['post_title']))  {
              $args['post_title'] = $_POST['acpt']['insert']['post_title'];
            }

           $this->added_post_id = wp_insert_post($args);
          }
        } else {
          acpt_save::save_post_fields($this->store, $this);
        }

      }

      $this->method = $opts['method'];
      $field = '<form id="'.$name.'" method="post" ';
      $field .= is_string($opts['action']) ? 'action="'.$opts['action'].'" ' : 'action="'.esc_attr($_SERVER["REQUEST_URI"]).'" ';
      $field .= '>';
    }

    if(is_string($opts['action'])) $this->action = $opts['action'];

    if(isset($field)) echo $field;
    wp_nonce_field('nonce_actp_nonce_action','nonce_acpt_nonce_field');
    echo '<input type="hidden" name="'.$this->saveName.'" value="true" />';
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
    $opts = $this->set_empty_keys($opts, array('type'));
    $field = '';
    if($name) :
      $field = $opts['type'] == 'button' ? '<input type="button"' : '<input type="submit"';
      $field .= 'value="'.esc_attr($name).'" class="button-primary" />';
    endif;
    $field .= '</form>';

    echo $field;
  }

  function notice($message = null, $class = 'updated') {
    if(is_string($message)) {
      $this->saveMessage = $message;
    }


    if(isset($_POST[$this->saveName])) : ?>
      <div class="<?php echo $class; ?>">
        <p><?php echo $this->saveMessage; ?></p>
      </div>
    <?php endif;

  }

  /**
   * Buffer Output
   *
   * Use buffering to catch output from the forms api and index in into
   * the buffer attribute. To index recall buffer and add a name. Access
   * the buffer in the object $this->buffer[index]
   *
   * If you are buffering the whole form it the data with main.
   *
   * @param null $index
   *
   * @return $this
   */
  function buffer($index = null) {
    if($this->echo === true ) {

      $index = $this->sanitize_name($index);

      if($this->buffering === false ) {
        if(isset($index) && $index !== '') {
          die('Making Form: Starting buffer... Index when the buffer ends.');
        }
        ob_start();
        $this->buffering = true;
      } else {
        $this->test_for($index, 'Making Form: Ending buffer... add an index.');
        $data = ob_get_clean();
        $this->buffer[$index] = $data;
        $this->buffering = false;
      }

    }

    return $this;
  }

  /**
   * Form Text.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function text($name, $opts=array(), $label = null) {
    return include 'fields/text/method.php';
  }

  /**
   * Form Text.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function title($name, $opts=array(), $label = null) {
    return include 'fields/title/method.php';
  }

  /**
   * Form Email.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function user_email($name, $opts=array(), $label = null, $user_id) {
    return include 'fields/email/method.php';
  }

  /**
   * Form Hidden.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function hidden($name, $opts=array(), $label = null) {
    return include 'fields/hidden/method.php';
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
  function color($name, $opts=array(), $label = null) {
    return include 'fields/color/method.php';
  }

  /**
   * Form Checkbox
   *
   * this function works well for making a form element {@link get_color_form()}
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function checkbox($name, $opts=array(), $label = null) {
    return include 'fields/checkbox/method.php';
  }

  /**
   * Form Textarea.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function textarea($name, $opts=array(), $label = null) {
    return include 'fields/textarea/method.php';
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
  function select($name, $options=array('Key' => 'Value'), $opts=array(), $label = null) {
    return include 'fields/select/method.php';
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
  function radio($name, $options=array('Key' => 'Value'), $opts=array(), $label = null) {
    return include 'fields/radio/method.php';
  }

  /**
   * Form Text Repeater.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function text_repeater($name, $opts=array(), $label = null) {
    return include 'fields/text_repeater/method.php';
  }

  /**
   * Form WP Editor.
   *
   * In the $editor_setteings set array('teeny' => true) to have a smaller editor.
   * Note that it is not a good idea to use this in a meta box or in a hidden area
   * as TinyMCE can be buggy. http://core.trac.wordpress.org/ticket/22168
   *
   *
   * @param string $name singular name is required
   * @param bool $label text for the label
   * @param array $opts args override and extend wp_editor
   * @param array $editor_settings
   * @return $this
   */
  function editor($name, $label=null, $opts=array(), $editor_settings = array()) {
    return include 'fields/editor/method.php';
  }

  /**
   * Form Image
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function image($name, $opts=array(), $label = null) {
    return include 'fields/image/method.php';
  }

  /**
   * Form Image Repeater
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function image_repeater($name, $opts=array(), $label = null) {
    return include 'fields/image_repeater/method.php';
  }

  /**
   * Form File
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function file($name, $opts=array(), $label = null) {
    return include 'fields/file/method.php';
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
    return include 'fields/google_map/method.php';
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
    return include 'fields/date/method.php';
  }

  /**
   * Get Image Form
   *
   * @param $o
   *
   * @return string
   */
  function get_image_form($o) {
    return include 'fields/image/get.php';
  }

  /**
   * Checkbox Get Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_checkbox_form($o) {
    $opts = $this->set_empty_keys($o['opts']);
    $desc = isset($opts['desc']) ? $opts['desc'] : $opts['label'];
    $group = $opts['group'];
    $sub = $opts['sub'];
    $s = $this->get_opts($o['name'], $opts, $o['field'], $o['label']);
    $v = $this->get_field_value($o['field'], $group, $sub);

    if( $v == 1 ) $checked = array('checked' => 'checked');
    else $checked = array();

    $attr = array(
      'class' => "{$o['classes']}  acpt_{$o['field']} {$s['class']}",
      'type' => 'checkbox',
      'value' => 1,
      'name' => $s['name'],
      'id' => $s['id'],
      'readonly' => $s['read']
    );

    $attr = array_merge($attr, $checked);

    $input = acpt_html::input($attr);

    $l = acpt_html::element('none', array('html' => " {$desc}" ), null);

    $default = acpt_html::element('input', array(
        'type' => 'hidden',
        'name' => $s['name'],
        'value' => '0'
      ), null);

    $field = acpt_html::element('label', array(
        'html' => array($default, $input, $l)
      ));

    ;

    $dev_note = $this->dev_message($o['field'], $group, $sub);

    return $s['bLabel'].$s['label'].$s['aLabel'].$field.$o['html'].$dev_note.$s['help'].$s['aField'];
  }

  /**
   * Get Google Map Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_google_map_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);

    // setup for grouping
    $group = $this->get_opt_by_test($o['opts']['group'], '');
    $sub = $this->get_opt_by_test($o['opts']['sub'], '');
    $field = $o['field'].'_encoded';

    $value = $this->get_field_value($field, $group, $sub);
    $name = $this->get_acpt_post_name($field, $group, $sub);

    // set http
    if (is_ssl()) $http = 'https://';
    else $http = 'http://';

    // zoom
    if(empty($value)) $zoom = 1;
    else $zoom = 15;

    $attrName = acpt_html::make_html_attr('name', $name);

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
    $o['opts'] = $this->set_empty_keys($o['opts']);

    // setup for grouping
    $group = $this->get_opt_by_test($o['opts']['group'], '');
    $sub = $this->get_opt_by_test($o['opts']['sub'], '');
    $field = $o['field'].'_id';

    $value = $this->get_field_value($field, $group, $sub);
    $name = $this->get_acpt_post_name($field, $group, $sub);

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

    $attrName = acpt_html::make_html_attr('name', $name); // $o['field'].'_id', $o['opts']['group'], $o['opts']['sub']);

    $o['html'] = "<input type=\"hidden\" class=\"attachment-id-hidden\" {$attrName} {$valueID}>";
    $o['html'] .= '<input type="button" class="button upload-button" value="'.$button.'"> <span class="clear-attachment">Remove</span>';

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
    $o['opts'] = $this->set_empty_keys($o['opts']);
    $s = $this->get_opts($o['name'], $o['opts'], $o['field'], $o['label']);
    $v = $this->get_field_value($o['field'], $o['opts']['group'], $o['opts']['sub']);

    $field = acpt_html::input(array(
        'class' => "{$o['classes']}  acpt_{$o['field']} {$s['class']}",
        'type' => 'text',
        'value' => esc_attr($v),
        'name' => $s['name'],
        'id' => $s['id'],
        'readonly' => $s['read']
    ), true);

    $dev_note = $this->dev_message($o['field'], $o['opts']['group'], $o['opts']['sub']);

    return $s['bLabel'].$s['label'].$s['aLabel'].$field.$o['html'].$dev_note.$s['help'].$s['aField'];
  }

  /**
   * Get Title Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_title_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);
    $s = $this->get_opts($o['name'], $o['opts'], $o['field'], $o['label']);

    if(!empty($o['value'])) {
      $v = $o['value'];
    } else {
      $v = $this->get_field_value($o['field'], $o['opts']['group'], $o['opts']['sub']);
    }

    $field = acpt_html::input(array(
      'class' => "{$o['classes']}  acpt_{$o['field']} {$s['class']}",
      'type' => 'text',
      'value' => esc_attr($v),
      'name' => $s['name'],
      'id' => $s['id'],
      'readonly' => $s['read']
    ), true);

    $dev_note = $this->dev_message($o['field'], $o['opts']['group'], $o['opts']['sub']);

    return $s['bLabel'].$s['label'].$s['aLabel'].$field.$o['html'].$dev_note.$s['help'].$s['aField'];
  }

  /**
   * Get User Email Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_email_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);
    $s = $this->get_opts($o['name'], $o['opts'], $o['field'], $o['label']);

    if(!empty($o['value'])) {
      $v = $o['value'];
    } else {
      $v = $this->get_field_value($o['field'], $o['opts']['group'], $o['opts']['sub']);
    }

    $field = acpt_html::input(array(
      'class' => "{$o['classes']}  acpt_{$o['field']} {$s['class']}",
      'type' => 'text',
      'value' => esc_attr($v),
      'name' => $s['name'],
      'id' => $s['id'],
      'readonly' => $s['read']
    ), true);

    $dev_note = $this->dev_message($o['field'], $o['opts']['group'], $o['opts']['sub']);

    return $s['bLabel'].$s['label'].$s['aLabel'].$field.$o['html'].$dev_note.$s['help'].$s['aField'];
  }

  /**
   * Get Hidden Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_hidden_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);
    $s = $this->get_opts($o['name'], $o['opts'], $o['field'], $o['label']);

    if(!empty($o['value'])) {
      $v = $o['value'];
    } else {
      $v = $this->get_field_value($o['field'], $o['opts']['group'], $o['opts']['sub']);
    }

    $field = acpt_html::input(array(
      'class' => "{$o['classes']}  acpt_{$o['field']} {$s['class']}",
      'type' => 'hidden',
      'value' => esc_attr($v),
      'name' => $s['name'],
      'id' => $s['id'],
      'readonly' => $s['read']
    ), true);

    $dev_note = $this->dev_message($o['field'], $o['opts']['group'], $o['opts']['sub']);

    return $s['bLabel'].$s['label'].$s['aLabel'].$field.$o['html'].$dev_note.$s['help'].$s['aField'];
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
    $group = $this->get_opt_by_test($opts['group'], '');
    $sub = $this->get_opt_by_test($opts['sub'], '');
    $s['name'] = $this->get_acpt_post_name($fieldName, $group, $sub);
    $s['id'] = 'acpt_'.$fieldName;

    // label
    $labelSettings = $this->get_input_label($s, $opts, $name, $label);
    $s = array_merge($s, $labelSettings);

    return $s;
  }

  /**
   * Setup Label Data
   *
   * Grab label data from the form object and from each inputs settings.
   *
   * @param $s
   * @param $opts
   * @param $name
   * @param $label
   *
   * @return mixed
   */
  private function get_input_label($s, $opts, $name, $label) {

    // is there a label at all?
    if(is_null($label) && is_bool($this->label)) {
      $label = $this->label;
    } elseif( is_null($label) ) {
      $label = true;
    }

    if( is_string($this->bLabel) && is_null($opts['bLabel']) ) {
      $opts['bLabel'] = $this->bLabel;
    }

    if( is_string($this->aLabel) && is_null($opts['aLabel'])) {
      $opts['aLabel'] = $this->aLabel;
    }

    if( is_string($this->aField) && is_null($opts['aField'])) {
      $opts['aField'] = $this->aField;
    }

    $opts['labelTag'] = $this->get_opt_by_test($this->labelTag, $opts['labelTag']);

    $s['bLabel'] = is_null($opts['bLabel']) ? BEFORE_LABEL : $opts['bLabel'];
    $s['aLabel'] = is_null($opts['aLabel']) ? AFTER_LABEL : $opts['aLabel'];
    $s['aField'] = is_null($opts['aField']) ? AFTER_FIELD : $opts['aField'];
    $opts['labelTag'] = $this->get_opt_by_test($opts['labelTag'], 'label');

    // show label?
    if($label === true) :
      $s['label'] = acpt_html::element($opts['labelTag'], array(
          'class' => 'control-label',
          'for' => $s['id'],
          'html' => $this->get_opt_by_test($opts['label'], $name)
        ));
    else :
      $s['label'] = '';
    endif;

    return $s;
  }

  /**
   * Get Dev Note
   *
   * Add the dev field to the admin to see the a acpt_meta() function
   *
   * @param $fieldName
   * @param $group
   * @param $sub
   *
   * @return string
   */
  protected function dev_message($fieldName, $group, $sub) {
    global $post;
    $group = $this->get_opt_by_test($group, $this->group);

    if($this->store == 'options') {
      $getter = 'option';
    } elseif($this->store == 'user_meta') {
      $getter = 'user_meta';
    }
    elseif(isset($post)) {
      $getter = 'meta';
    }

    if(DEV_MODE == true) :
      $v = "acpt_{$getter}('{$group}[{$fieldName}]{$sub}');";
      $data = acpt_html::input(array(
          'class' => 'dev_note',
          'readonly' => true,
          'value' => esc_attr($v)
        ), true);
    else :
      $data = '';
    endif;

    if(!is_admin()) {
      $data = '';
    }

    return $data;
  }

  /**
   * Get Field Name
   *
   * @param $name
   *
   * @return string
   */
  protected function get_field_name($name) {
    $name = $this->sanitize_name($name);
    return $this->name.'_'.$name;
  }
  /**
   * Get Field Value
   *
   * Get the value if it is a post type or another page form
   *
   * @param mixed|string $field
   * @param string $group
   * @param string $sub
   *
   * @return mixed|null|string
   */
  protected function get_field_value($field, $group, $sub) {
    global $post;
    $group = $this->get_opt_by_test($group, $this->group);

    if($this->store == 'options') :
      $value = acpt_get::option("{$group}[{$field}]{$sub}");
    elseif($this->store == 'user_meta') :
      $value = acpt_get::user_meta("{$group}[{$field}]{$sub}", $this->user);
    elseif(isset($post->ID)) :
      $value = acpt_get::meta("{$group}[{$field}]{$sub}");
    endif;

    return $value;
  }

  /**
   * Get $_POST Name
   *
   * This will set the name value for a field
   *
   * @param $field
   * @param string $group
   * @param string $sub
   *
   * @return string
   */
  private function get_acpt_post_name($field, $group, $sub ) {
    $post_name = $this->get_bracket_syntax($field, $group, $sub);

    return "acpt{$post_name}";
  }

  /**
   * Compile bracket syntax for usage
   *
   * @param $field
   * @param $group
   * @param $sub
   *
   * @return string
   */
  private function get_bracket_syntax($field, $group, $sub ) {
    $group = $this->get_opt_by_test($group, $this->group);

    if(!acpt_validate::bracket($group) && $group != '' ) {
      $this->test_for(false, 'ACPT ERROR: You need to to the form group to an array format ['.$group.']');
    }

    if(!acpt_validate::bracket($sub) && $sub != '' ) {
      $this->test_for(false, 'ACPT ERROR: You need to to the form sub group to an array format ['.$group.']');
    }

    return "{$group}[{$field}]{$sub}";
  }

}