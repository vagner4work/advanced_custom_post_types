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

	function make_computer_name($name) {
		$pattern = '/(\s+)/';
		$replacement = '_';
		$computerName = preg_replace($pattern,$replacement,strtolower(trim($name)));
		return $computerName;
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
		  wp_nonce_field('actp_nonce_action','acpt_nonce_field');
	    echo '<input type="hidden" name="acpt" value="true" />';
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

	function get_opts($name, $opts, $fieldName, $label) {

		if(!$opts['labelTag']) $opts['labelTag'] = 'label';

		if ( is_string($opts['class']) ) $setup['class'] = $opts['class'];

		$setup['id'] = 'id="'.$fieldName.'"';

		if ( isset($opts['readonly']) ) $setup['readonly'] = 'readonly="readonly"';

		if ( is_integer($opts['size']) ) $setup['size'] = 'size="'.$opts['size'].'"';

		if ( is_string($fieldName) ) $setup['nameAttr'] = 'name="'.$fieldName.'"';

		// label
		if(isset($label)) :
			$labelName = (isset($opts['label']) ? $opts['label'] : $name);
			$setup['label'] = '<'.$opts['labelTag'].' class="control-label" for="'.$fieldName.'">'.$labelName.'</'.$opts['labelTag'].'>';
		endif;

		// beforeLabel
		if($opts['beforeLabel']) :
			$setup['beforeLabel'] = $opts['beforeLabel'];
		else :
			$setup['beforeLabel'] = BEFORE_LABEL;
		endif;

		// afterLabel
		if($opts['afterLabel']) :
			$setup['afterLabel'] = $opts['afterLabel'];
		else :
			$setup['afterLabel'] = AFTER_LABEL;
		endif;

		// afterField
		if($opts['afterField']) :
			$setup['afterField'] = $opts['afterField'];
		else :
			$setup['afterField'] = AFTER_FIELD;
		endif;

		return $setup;
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
	  global $post;

	  $dev_note = null;
	  $fieldName = 'acpt_'.$this->formName.'_text_'.$name;

	  // value
	  if($value = get_post_meta($post->ID, $fieldName, true)) :
	      $value = 'value="'.$value.'"';
	  endif;

	  $setup = $this->get_opts($name, $opts, $fieldName, $label);

	  $field = "<input type=\"text\" class=\"text $fieldName {$setup['class']}\" {$setup['id']} {$setup['size']} {$setup['readonly']} {$setup['nameAttr']} $value />";
	  if(DEV_MODE == true) $dev_note = '<p class="dev_note">get_post_meta($post->ID, ' . $fieldName . ', true);</p>';

	  echo apply_filters($fieldName . '_filter', $setup['beforeLabel'].$setup['label'].$setup['afterLabel'].$field.$dev_note.$setup['afterField']);
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
	  $fieldName = 'acpt_'.$this->formName.'_textarea_'.$name;

	  // value
	  if($value = get_post_meta($post->ID, $fieldName, true)) :
	      $value;
	  endif;

	  $setup = $this->get_opts($name, $opts, $fieldName, $label);

	  $field = "<textarea class=\"textarea $fieldName {$setup['class']}\" {$setup['id']} {$setup['size']} {$setup['readonly']} {$setup['nameAttr']} />$value</textarea>";
	  if(DEV_MODE == true) $dev_note = '<p class="dev_note">get_post_meta($post->ID, ' . $fieldName . ', true);</p>';

	  echo apply_filters($fieldName . '_filter', $setup['beforeLabel'].$setup['label'].$setup['afterLabel'].$field.$dev_note.$setup['afterField']);
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
		$fieldName = 'acpt_'.$this->formName.'_select_'.$name;

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

				$optionsList .= "<option $selected value=\"$option\">$option</option>";
			endforeach;

		endif;

		$setup = $this->get_opts($name, $opts, $fieldName, $label);

		$field = "<select class=\"select $fieldName {$setup['class']}\" {$setup['id']} {$setup['size']} {$setup['readonly']} {$setup['nameAttr']} />$optionsList</select>";
		if(DEV_MODE == true) $dev_note = '<p class="dev_note">get_post_meta($post->ID, ' . $fieldName . ', true);</p>';

		echo apply_filters($fieldName . '_filter', $setup['beforeLabel'].$setup['label'].$setup['afterLabel'].$field.$dev_note.$setup['afterField']);
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
		$fieldName = 'acpt_'.$this->formName.'_radio_'.$name;

		// name
		if ( is_string($fieldName) ) :
			$nameAttr = 'name="'.$fieldName.'"';
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

		$field = "<div class=\"radio $fieldName {$setup['class']}\" {$setup['id']} />$optionsList</div>";
		if(DEV_MODE == true) $dev_note = '<p class="dev_note">get_post_meta($post->ID, ' . $fieldName . ', true);</p>';

		echo apply_filters($fieldName . '_filter', $setup['beforeLabel'].$setup['label'].$setup['afterLabel'].$field.$dev_note.$setup['afterField']);
	}
    
	/**
	 * Form WP Editor.
	 *
	 * @param string $singular singular name is required
	 * @param array $opts args override and extend
	 */
	function editor($name, $opts=array()) {
	  if(!$this->formName) exit('Making Form: You need to make the form first.');
	  if(!$name) exit('Making Editor: You need to enter a singular name.');
	  global $post;
	  $fieldName = 'acpt_'.$this->formName.'_editor_'.$name;

	  if($value = get_post_meta($post->ID, $fieldName, true)) $content = $value;
	  wp_editor(
	      $content,
	      'wysisyg_'.$this->formName.'_'.$name,
	      array_merge($opts,array('textarea_name' => 'acpt_'.$this->formName.'_editor_'.$name))
	  );
		if(DEV_MODE == true) echo '<p class="dev_note">get_post_meta($post->ID, ' . $fieldName . ', true);</p>';
	}

}