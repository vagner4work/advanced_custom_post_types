<?php
$this->test_for($this->name, 'Making Form: You need to make the form first.');
$this->test_for($name, 'Making Form: You need to enter a singular name.');

$fieldName = $this->get_field_name($name);

$opts = $this->set_empty_keys($opts, array('group', 'sub'));
$group = $this->get_opt_by_test($opts['group'], '');
$sub = $this->get_opt_by_test($opts['sub'], '');

$v = $this->get_field_value($fieldName, $group, $sub);
$s = $this->get_opts($label, array('labelTag' => 'span'), $fieldName, true);

$noOverride = array(
  'textarea_name' => $this->get_acpt_post_name($fieldName, $group, $sub)
);

$defaultSettings = array(
  'textarea_rows' => 15,
  'tinymce' => array( 'plugins' => 'wordpress' )
);

$v = acpt_sanitize::editor($v);
$id = 'wysisyg'.$this->letterLower($fieldName);
$editor_settings = array_merge($defaultSettings, $editor_settings, $noOverride);

if($this->echo === false) { ob_start(); }
echo '<div class="control-group">';
echo $s['label'];

wp_editor($v,$id,$editor_settings);

echo $this->dev_message($fieldName, $group, $sub);
echo '</div>';
if($this->echo === false) {
  $data = ob_get_clean();
  $this->buffer['main'] .= $data;
}

return $this;