<?php
class form extends acpt {
    public $formName = null;

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
    }
    
    /**
     * Form Input.
     * 
     * @param string $singular singular name is required
     * @param array $opts args override and extend
     */
    function input($name, $opts=array(), $label = true) {
        if(!$this->formName) exit('Making Form: You need to make the form first.');
        if(!$name) exit('Making Input: You need to enter a singular name.');
        global $post;

        $field = '';
        if($label) $field .= '<label for="text_'.$this->formName.'_'.$name.'">'.$name.'</label>';
        $field .= '<input type="text" id="text_'.$this->formName.'_'.$name.'" name="acpt_'.$this->formName.'_'.$name.'" ';
        $field .= isset($opts['class']) ? 'class="'.$opts['class'].'" ' : 'class="text" ';
        if(isset($opts['size'])) $field .= 'size="'.$opts['size'].'" ';
        if(isset($opts['maxlength'])) $field .= 'maxlength="'.$opts['maxlength'].'" ';
        if($value = get_post_meta($post->ID, 'acpt_'.$this->formName.'_'.$name, true)) $field .= 'value="'.$value.'"';
        $field .= '/>';
        
        echo $field;
    }
    
    /**
     * Form Textarea.
     * 
     * @param string $singular singular name is required
     * @param array $opts args override and extend
     */
    function textarea($name, $opts=array()) {
        if(!$this->formName) exit('Making Form: You need to make the form first.');
        if(!$name) exit('Making Textarea: You need to enter a singular name.');
        global $post;

        $field = '';
        if($label) $field .= '<label for="text_'.$this->formName.'_'.$name.'">'.$name.'</label>';
        $field .= '<textarea id="textarea_'.$this->formName.'_'.$name.'" name="acpt_'.$this->formName.'_'.$name.'"';
        $field .= isset($opts['class']) ? ' class="'.$opts['class'].'"' : ' class="textarea"';
        $field .= '>';
        if($value = get_post_meta($post->ID, 'acpt_'.$this->formName.'_'.$name, true)) $field .= $value;
        $field .= '</textarea>';
        
        echo $field;
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

        if($value = get_post_meta($post->ID, 'acpt_'.$this->formName.'_'.$name, true)) $content = $value;
        wp_editor(
            $content,
            'wysisyg_'.$this->formName.'_'.$name,
            array_merge($opts,array('textarea_name' => 'acpt_'.$this->formName.'_'.$name))
        );
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

        $this->formName = null;
        
        if(isset($field)) echo $field;
    }
}   