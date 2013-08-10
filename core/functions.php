<?php
/**
 * Get Meta Access Function
 *
 * Get the post meta out of the DB table {prefix}_postmeta.
 * Though it is only a interface for acpt_get::meta it is the preferred method to
 * access data within theme template and plugin files.
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

/**
 * Echo Meta Data
 *
 * Echo a single string value gotten from acpt_meta().
 *
 * @param string $name
 * @param string $fallBack
 * @param bool $groups
 * @param null $id
 */
function e_acpt_meta($name = '', $fallBack = '', $groups = true, $id = null) {
  $data = acpt_meta($name, $fallBack, $groups, $id);
  ($fallBack !== '' ) ? true : $fallBack = 'No string data '.$name;
  is_string($data) ? true : $data = $fallBack;
  echo $data;
}