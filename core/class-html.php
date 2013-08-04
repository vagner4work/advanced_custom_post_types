<?php
class acpt_html extends acpt {

  private static $noCloseTags = array('img' => true, 'input' => true, 'hr' => true, 'br' => true);

  /**
   * Make HTML
   *
   * This is a function that will help you make html without playing with too much.
   * It is very helpful when you need to make dynamic html.
   *
   * $html needs to be an array within an array as follows
   *
   * @param array $html
   *
   * @return string
   */
  static function make_html($html) {
    $output = '';
    $s = ' ';
    $count = count($html);

    if(is_array($html)) : for($i = 0; $i < $count; $i++) :
      foreach($html[$i] as $tag => $attributes)  :
        $output .= self::check_none($tag,  '<' . $tag . $s);

        foreach($attributes as $attr => $value) :
          if($attr != 'html') $output .= self::make_html_attr($attr, $value) . $s;
        endforeach;

        if(array_key_exists($tag, self::get_close_tags()) ) :
          $output .= self::check_none($tag,  '/>');
        else :
          $output .= self::check_none($tag,  '>');
          $output .= self::check_content($attributes['html']);
          $output .= self::check_none($tag, '</'.$tag.'>');
        endif;

      endforeach;
    endfor; endif;

    return $output;
  }

  /**
   * Make HTML Attributes
   *
   * Check if value is null. If so skip the attr.
   *
   * @param $attr
   * @param string $value
   *
   * @return string
   */
  static function make_html_attr($attr, $value='') {
    if($value != '') return "{$attr}=\"{$value}\"";
    else return '';
  }

  private function check_none($tag, $output) {
    if($tag != 'none') { return $output; }
    else { return ''; }
  }

  private function check_content($html) {
    if(is_string($html)) :
      return $html;
    elseif(isset($html) && is_array($html)) :
      return self::make_html($html);
    else :
      return '';
    endif;
  }

  static function make_input($args, $make = null) {
    $args = array('input' => $args );

    if(isset($make)) {
      return self::make_html(array($args));
    } else {
      return $args;
    }
  }

  private function get_close_tags() {
    return self::$noCloseTags;
  }

}