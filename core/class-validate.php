<?php
/*
This is a fork of Devin Price's code for theme options framework.

Contributors: Devin Price
Tags: options, theme options
Donate link: http://bit.ly/options-donate-2
Requires at least: 3.3
Tested up to: 3.5
Stable tag: 1.6
License: GPLv2
*/

class acpt_sanitize extends acpt {

  static function textarea($input) {
    global $allowedposttags;
    $output = wp_kses( $input, $allowedposttags);
    return $output;
  }

  static function editor($input) {
    if ( current_user_can( 'unfiltered_html' ) ) {
    $output = $input;
    }
    else {
      global $allowedtags;
      $output = wpautop(wp_kses( $input, $allowedtags));
    }
    return $output;
  }

  static function hex( $hex, $default = '' ) {
    if ( of_validate_hex( $hex ) ) {
      return $hex;
    }
    return $default;
  }

}

class acpt_validate extends acpt {

  /* numeric, decimal passes */
  static function numeric($num) {
    return is_numeric($num);
  }

  /* digits only, no dots */
  static function digits($digit) {
    return !preg_match ("/[^0-9]/", $digit);
  }

  static function hex( $hex ) {
    $hex = trim( $hex );
    /* Strip recognized prefixes. */
    if ( 0 === strpos( $hex, '#' ) ) {
      $hex = substr( $hex, 1 );
    }
    elseif ( 0 === strpos( $hex, '%23' ) ) {
      $hex = substr( $hex, 3 );
    }
    /* Regex match. */
    if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
      return false;
    }
    else {
      return true;
    }
  }
}