<?php
/**
 * Get Meta Access Function
 *
 * @param string $name
 * @param string $fallBack
 * @param bool $groups
 * @param null $id
 *
 * @return mixed|null|string
 */
function acpt_meta($name = '', $fallBack = '', $groups = true, $id = null) {
  return acpt_get::meta($name, $fallBack, $groups, $id);
}

function e_acpt_meta($name = '', $fallBack = '', $groups = true, $theID = null) {
  $data = acpt_meta($name, $groups, $fallBack, $theID);
  ($fallBack !== '' ) ? true : $fallBack = 'No string data '.$name;
  is_string($data) ? true : $data = $fallBack;
  echo $data;
}