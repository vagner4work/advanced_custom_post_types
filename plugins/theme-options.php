<?php

add_action('admin_menu', 'acpt_add_themeOptions');

function acpt_add_themeOptions() {
  add_submenu_page('themes.php', 'Theme Options', 'Theme Options', 'edit_theme_options', 'theme_acpt_options', 'acpt_pageContent_themeOptions');
}


function acpt_pageContent_themeOptions() {
  ?>
  <div class="wrap">
  <?php screen_icon('themes'); ?> <h2>Theme Options</h2>
  <?php
  $form = acpt_form('options', array('group' => '[acpt_options]', 'method' => true));
  $form->notice();
  $form->buffer()
    ->checkbox('Checkbox Example', array('desc' => 'Select this for value of 1'))
    ->text('Text Field')
    ->image('Image Field')
    ->file('File Field')
    ->textarea('Textarea')
    ->buffer('general')
    ->buffer()
    ->color('Color Field', array('palette' => array('#fff', '#f00', '#f30')))
    ->color('Color Field (no palette)')
    ->google_map('Address Field')
    ->date('Date Field')
    ->editor('WYSIWYG Editor')
    ->select('Select List', array('one', 'two', 'three'))
    ->select('Select List Key', array('One' => '1', 'Two' => '2'), array('select_key' =>  true))
    ->radio('radio', array('blue', 'green', 'red'), array('label' => 'Radio Buttons'))
    ->buffer('advanced');


  $screen = new acpt_options();
  $screen->add_tab( array(
      'id' => 'general',
      'title' => "General",
      'content' => $form->buffer['general']
    ) )
    ->add_tab( array(
        'id' => 'advanced',
        'title' => "Advanced",
        'content' => $form->buffer['advanced']
      ) )
    ->add_tab( array(
        'id' => 'help',
        'title' => "Help",
        'content' => '',
        'callback' => 'acpt_help_text'
      ) );

  $screen->set_sidebar('<input type="submit" value="Save Changes" class="button-primary" />');
  $screen->make();

  $form->end();
  ?>
  </div>
  <?php
}

function acpt_help_text() {
  ?>
  This is some example text for the plugin using multiple tabs. This might be helpful to show how to add more than one tab.
<?php
}