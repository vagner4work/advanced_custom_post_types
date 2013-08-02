<?php
/**
  * Form Builder
  *
  * This is the long description for a DocBlock. This text may contain
  * multiple lines and even some _markdown_.
  *
  * * Markdown style lists function too
  * * Just try this out once
  *
  * The section after the long description contains the tags; which provide
  * structured meta-data concerning the given element.
  *
  * @author  Kevin Dees
  *
  * @since 0.6
  * @version 0.6
  *
  * @global string $acpt_version
  */
class form {
  public $formName = null;

	function __construct($name, $opts=array()) {
		$this->make($name, $opts);
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}

  /**
   * Make Form.
   *
   * @param string $singular singular name is required
   * @param array $opts args override and extend
   */
  function make($name, $opts=array()) {
      if(!$name) exit('Making Form: You need to enter a singular name.');

      if(isset($opts['method'])) :
          $field = '<form id="'.$name.'" ';
          $field .= isset($opts['method']) ? 'method="'.$opts['method'].'" ' : 'method="post" ';
          $field .= isset($opts['action']) ? 'action="'.$opts['action'].'" ' : 'action="'.$name.'" ';
          $field .= '>';
      endif;

      $this->formName = $name;

      if(isset($field)) echo $field;
		  wp_nonce_field('nonce_actp_nonce_action','nonce_acpt_nonce_field');
	    echo '<input type="hidden" name="save_acpt" value="true" />';
  }

	/**
	 * End Form.
	 *
	 * @param string $singular singular name is required
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
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function text($name, $opts=array(), $label = true) {
	  if(!$this->formName) exit('Making Form: You need to make the form first.');
	  if(!$name) exit('Making Input: You need to enter a singular name.');

		$type = 'text';
		$fieldName = $this->get_field_name($name, $type);
		$html = '';

		// $name, $opts, $classes, $fieldName, $label, $type
		$input_fields = $this->get_text_form($name, $opts, "$type", $fieldName, $label, $type, $html);

	  echo apply_filters($fieldName . '_filter', $input_fields);
	}

	/**
	 * Form URL.
	 *
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function url($name, $opts=array(), $label = true) {
		if(!$this->formName) exit('Making Form: You need to make the form first.');
		if(!$name) exit('Making Input: You need to enter a singular name.');

		$type = 'url';
		$fieldName = $this->get_field_name($name, $type);
		$html = '';

		// $name, $opts, $classes, $fieldName, $label, $type
		$input_fields = $this->get_text_form($name, $opts, "$type", $fieldName, $label, $type, $html);

		echo apply_filters($fieldName . '_filter', $input_fields);
	}

	/**
	 * Form Textarea.
	 *
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function textarea($name, $opts=array(), $label = true) {
	  if(!$this->formName) exit('Making Form: You need to make the form first.');
	  if(!$name) exit('Making Textarea: You need to enter a singular name.');
	  global $post;

	  $dev_note = null;
		$fieldName = $this->get_field_name($name, $opts, 'textarea');

	  // value
	  if($value = get_post_meta($post->ID, $fieldName, true)) :
	      $value;
	  endif;

	  $setup = $this->get_opts($name, $opts, $fieldName, $label);

	  @$field = "<textarea class=\"textarea $fieldName {$setup['class']}\" {$setup['id']} {$setup['size']} {$setup['readonly']} {$setup['nameAttr']} />$value</textarea>";
		$dev_note = $this->dev_message($fieldName);

	  echo apply_filters($fieldName . '_filter', $setup['beforeLabel'].$setup['label'].$setup['afterLabel'].$field.$dev_note.$setup['help'].$setup['afterField']);
	}

	/**
	 * Form Select.
	 *
	 * @param string $singular singular name is required
	 * @param array $options args for items
	 * @param array $opts args override and extend
	 */
	function select($name, $options=array('Key' => 'Value'), $opts=array(), $label = true) {
		if(!$this->formName) exit('Making Form: You need to make the form first.');
		if(!$name) exit('Making Textarea: You need to enter a singular name.');
		global $post;

		$dev_note = null;
		$fieldName = $this->get_field_name($name, 'select');

		// get options HTML
		if(isset($options)) :
			$options;

			$optionsList = '';
			$value = get_post_meta($post->ID, $fieldName, true);

			foreach( $options as $key => $option) :
				if($option == $value)
					$selected = 'selected="selected"';
				else
					$selected = null;

				if(array_key_exists('select_key', $opts) && $opts['select_key'] == true)
					$key = $key;
				else
					$key = $option;

				$optionsList .= "<option $selected value=\"$option\">$key</option>";
			endforeach;

		endif;

		$setup = $this->get_opts($name, $opts, $fieldName, $label);

		@$field = "<select class=\"select $fieldName {$setup['class']}\" {$setup['id']} {$setup['size']} {$setup['readonly']} {$setup['nameAttr']} />$optionsList</select>";
		$dev_note = $this->dev_message($fieldName);

		echo apply_filters($fieldName . '_filter', $setup['beforeLabel'].$setup['label'].$setup['afterLabel'].$field.$dev_note.$setup['help'].$setup['afterField']);
	}

	/**
	 * Form Radio.
	 *
	 * @param string $singular singular name is required
	 * @param array $options args for items
	 * @param array $opts args override and extend
	 */
	function radio($name, $options=array('Key' => 'Value'), $opts=array(), $label = true) {
		if(!$this->formName) exit('Making Form: You need to make the form first.');
		if(!$name) exit('Making Textarea: You need to enter a singular name.');
		global $post;

		$dev_note = null;
		$opts['labelTag'] = 'span';
		$fieldName = $this->get_field_name($name, 'radio');

		// name
		if ( is_string($fieldName) ) :
			$nameAttr = 'name="acpt['.$fieldName.']"';
		endif;

		// get options HTML
		if(isset($options)) :
			$options;

			$optionsList = '';
			$value = get_post_meta($post->ID, $fieldName, true);

			foreach( $options as $key => $option) :
				if($option == $value)
					$checked = 'checked="checked"';
				else
					$checked = null;

				$optionsList .= "<label><input type=\"radio\" $nameAttr $checked value=\"$option\" /><span>$option</span></label>";

			endforeach;

		endif;

		$setup = $this->get_opts($name, $opts, $fieldName, $label);

		@$field = "<div class=\"radio $fieldName {$setup['class']}\" {$setup['id']} />$optionsList</div>";
		$dev_note = $this->dev_message($fieldName);

		echo apply_filters($fieldName . '_filter', $setup['beforeLabel'].$setup['label'].$setup['afterLabel'].$field.$dev_note.$setup['help'].$setup['afterField']);
	}

	/**
	 * Form WP Editor.
	 *
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function editor($name, $label=null, $opts=array(), $settings=array('validate'=>'none')) {
	  if(!$this->formName) exit('Making Form: You need to make the form first.');
	  if(!$name) exit('Making Editor: You need to enter a singular name.');
	  global $post;

		$fieldName = $this->get_field_name($name, 'editor');

	  if($value = get_post_meta($post->ID, $fieldName, true))
		  $content = $value;
		else
			$content = '';

		$setup = $this->get_opts($label, array('labelTag' => 'span'), $fieldName, true);

		echo '<div class="control-group">';
		echo $setup['label'];
	  wp_editor(
	      $content,
	      'wysisyg_'.$this->formName.'_'.$name,
	      array_merge($opts,array('textarea_name' => 'acpt['.$fieldName.']'))
	  );
		echo $this->dev_message($fieldName);
		echo '</div>';
	}

	/**
	 * Form Image
	 *
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function image($name, $opts=array(), $label = true) {
		if(!$this->formName) exit('Making Form: You need to make the form first.');
		if(!$name) exit('Making Input: You need to enter a singular name.');
		global $post;

		$type = 'image';
		$fieldName = $this->get_field_name($name, $type);
    $valueID = $html = '';

		if(empty($opts['readonly'])) $opts['readonly'] = true;

		$placeHolderImage = '';

		// button
		if(isset($opts['button'])) :
			$button = $opts['button'];
		else :
			$button = "Insert Image";
		endif;

		// placeholder image and image id value
		if($value = get_post_meta($post->ID, $fieldName, true)) :
      $value = esc_url($value);
			$placeHolderImage = '<img class="upload-img" src="'.$value.'" />';
      $valueID = 'value="'.get_post_meta($post->ID, $fieldName."_id", true).'"';
		endif;

		$html .= "<input type=\"hidden\" class=\"attachment-id-hidden\" name=\"acpt[{$fieldName}_id]\" {$valueID}>";
		$html .= '<input type="button" class="button-primary upload-button" value="'.$button.'">';
		$html .= '<div class="image-placeholder"><a class="remove-image">remove</a>' . $placeHolderImage . '</div>';

		// $name, $opts, $classes, $fieldName, $label, $type
		$input_fields = $this->get_text_form($name, $opts, "$type upload-url", $fieldName, $label, $type, $html);

		echo apply_filters($fieldName . '_filter', $input_fields);
	}

	/**
	 * Form File
	 *
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function file($name, $opts=array(), $label = true) {
		if(!$this->formName) exit('Making Form: You need to make the form first.');
		if(!$name) exit('Making Input: You need to enter a singular name.');
		global $post;

		$type = 'file';
		$fieldName = $this->get_field_name($name, $type);
    $valueID = $html = '';

    if(empty($opts['readonly'])) $opts['readonly'] = true;

		// button
		if(isset($opts['button'])) :
			$button = $opts['button'];
		else :
			$button = "Insert File";
		endif;

    // placeholder image and image id value
    if(get_post_meta($post->ID, $fieldName."_id", true)) :
      $valueID = 'value="'.get_post_meta($post->ID, $fieldName."_id", true).'"';
    endif;

    $html .= "<input type=\"hidden\" class=\"attachment-id-hidden\" name=\"acpt[{$fieldName}_id]\" {$valueID}>";
		$html .= '<input type="button" class="button-primary upload-button" value="'.$button.'"> <span class="clear-attachment">clear file</span>';

		// $name, $opts, $classes, $fieldName, $label, $type
		$input_fields = $this->get_text_form($name, $opts, "$type upload-url", $fieldName, $label, $type, $html);

		echo apply_filters($fieldName . '_filter', $input_fields);
	}

	/**
	 * Google Maps.
	 *
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function google_map($name, $opts=array(), $label = true) {
		if(!$this->formName) exit('Making Form: You need to make the form first.');
		if(!$name) exit('Making Input: You need to enter a singular name.');
		global $post;

		$type = 'googleMap';
		$fieldName = $this->get_field_name($name, $type);
		$html = '';

		// set http
		if (is_ssl()) {
			$http = 'https://';
		} else {
			$http = 'http://';
		}

		// value
		if($value = get_post_meta($post->ID, $fieldName, true)) :
			$loc = urlencode($value);
			$zoom = 15;
		else :
			$zoom = 1;
			$loc = 'New+York,NY';
		endif;

		$html .= "<input type=\"hidden\" value=\"$loc\" name=\"acpt[{$fieldName}_encoded]\" />";
		$html .= '<p class="map"><img src="'.$http.'maps.googleapis.com/maps/api/staticmap?center='.$loc.'&zoom='.$zoom.'&size=1200x140&sensor=true&markers='.$loc.'" class="map-image" alt="Map Image" /></p>';

		// $name, $opts, $classes, $fieldName, $label, $type
		$input_fields = $this->get_text_form($name, $opts, "$type", $fieldName, $label, $type, $html);

		echo apply_filters($fieldName . '_filter', $input_fields);
	}

	/**
	 * Date.
	 *
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function date($name, $opts=array(), $label = true) {
		if(!$this->formName) exit('Making Form: You need to make the form first.');
		if(!$name) exit('Making Input: You need to enter a singular name.');
		global $post;

		$type = 'date';
		$fieldName = $this->get_field_name($name, $type);
		$html = '';

		// $name, $opts, $classes, $fieldName, $label, $type
		$input_fields = $this->get_text_form($name, $opts, "$type date-picker", $fieldName, $label, $type, $html);

		echo apply_filters($fieldName . '_filter', $input_fields);
	}

	/* Helper Functions
	--------------------------------------------------------------------------- */

	function get_field_name($name, $type) {
		return $this->formName.'_'.$name;
	}

	function get_opts($name, $opts, $fieldName, $label) {

		$setup['attr'] = '';

		// label
		if(empty($opts['labelTag'])) $opts['labelTag'] = 'label';

		// classes
		if ( is_string(isset($opts['class'])) ) $setup['class'] = $opts['class'];
		else $setup['class'] = '';

		// readonly
		if ( isset($opts['readonly']) ) {
			$setup['readonly'] = 'readonly="readonly"';
			$setup['attr'] .= ' '. $setup['readonly'];
		} else {
			$setup['readonly'] = '';
		}

		// size
		if ( array_key_exists('size', $opts) && is_integer($opts['size']) ) {
			$setup['size'] = 'size="'.$opts['size'].'"';
			$setup['attr'] .= ' '. $setup['size'];
		} else {
			$setup['size'] = '';
		}

		// name and id
		if ( isset($fieldName) ) {
			$setup['nameAttr'] = 'name="acpt['.$fieldName.']"';
			$setup['attr'] .= ' '. $setup['nameAttr'];

			$setup['id'] = 'id="acpt_'.$fieldName.'"';
			$setup['attr'] .= ' '. $setup['id'];
		}

		// label
		if(isset($label)) :
			$labelName = (isset($opts['label']) ? $opts['label'] : $name);
			$setup['label'] = '<'.$opts['labelTag'].' class="control-label" for="'.$fieldName.'">'.$labelName.'</'.$opts['labelTag'].'>';
		endif;

		// help text
		if(isset($opts['help'])) :
			$setup['help'] = '<p class="help-text">'.$opts['help'].'</p>';
		else :
			$setup['help'] = '';
		endif;

		// beforeLabel
		if(isset($opts['beforeLabel'])) :
			$setup['beforeLabel'] = $opts['beforeLabel'];
		else :
			$setup['beforeLabel'] = BEFORE_LABEL;
		endif;

		// afterLabel
		if(isset($opts['afterLabel'])) :
			$setup['afterLabel'] = $opts['afterLabel'];
		else :
			$setup['afterLabel'] = AFTER_LABEL;
		endif;

		// afterField
		if(isset($opts['afterField'])) :
			$setup['afterField'] = $opts['afterField'];
		else :
			$setup['afterField'] = AFTER_FIELD;
		endif;

		return $setup;
	}

	function dev_message($fieldName) {
		if(DEV_MODE == true) return "<input class=\"dev_note\" readonly value=\"acpt_meta('{$fieldName}');\" />";
		else return '';
	}

	function get_text_form($name, $opts, $classes, $fieldName, $label, $type, $html) {
		global $post;

		// setup
		$setup = $this->get_opts($name, $opts, $fieldName, $label);

		// value
		if($value = get_post_meta($post->ID, $fieldName, true)) :
			$value = 'value="'.$value.'"';
		endif;

		$classes = $classes . '  acpt_' . $fieldName . ' ' . $setup['class'];

		$field = "<input type=\"text\" class=\"{$classes}\" {$setup['attr']} $value />";
		$dev_note = $this->dev_message($fieldName);

		return $setup['beforeLabel'].$setup['label'].$setup['afterLabel'].$field.$html.$dev_note.$setup['help'].$setup['afterField'];
	}

	function make_computer_name($name) {
		$pattern = '/(\s+)/';
		$replacement = '_';
		$computerName = preg_replace($pattern,$replacement,strtolower(trim($name)));
		return $computerName;
	}

}