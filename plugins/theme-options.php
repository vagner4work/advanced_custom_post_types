<?php

add_action('admin_menu', 'acpt_add_themeOptions');

function acpt_add_themeOptions() {
  add_submenu_page('themes.php', 'Theme Options', 'Theme Options', 'edit_theme_options', 'theme_acpt_options', 'acpt_pageContent');
}


function acpt_pageContent() {
  ?>
  <div class="wrap">
  <?php screen_icon('themes'); ?> <h2>Theme Options</h2>
  <?php
  $form = acpt_form('options', array('group' => '[acpt_options]', 'method' => true));

  $form->buffer()
    ->checkbox('checkbox', array('label' => 'Checkbox Example', 'desc' => 'Select this for value of 1'))
    ->text('text', array('label' => 'Text Field', 'class' => 'example-class', 'help' => 'Example help text'))
    ->color('1', array('group' => '[details_colors]', 'label' => 'Color Field', 'default' => '#000', 'palette' => array('#fff', '#f00', '#f30')))
    ->buffer('general')
    ->buffer()
    ->color('2', array('group' => '[details_colors]', 'label' => 'Color Field (no palette)'))
    ->image('image', array('label' => 'Image Field', 'button' => 'Add Your Image', 'sub' => '[details_files]'))
    ->file('file', array('label' => 'File Field', 'button' => 'Select a File', 'sub' => '[details_files]'))
    ->google_map('address', array('label' => 'Address Field'))
    ->date('date', array('label' => 'Date Field', 'button' => 'Enter a Date', 'group' => '[details_adv]'))
    ->textarea('textarea', array('label' => 'Textarea', 'group' => '[details_adv]'))
    ->select('select', array('one', 'two', 'three'), array('label' => 'Select List', 'group' => '[details_adv]'))
    ->select('select_key', array('One' => '1', 'Two' => '2', 'Three' => '3'), array('label' => 'Select List Key', 'select_key' =>  true, 'group' => '[details_adv]'))
    ->radio('radio', array('blue', 'green', 'red'), array('label' => 'Radio Buttons', 'group' => '[details_adv]'))
    ->editor('editor', 'WYSIWYG Editor', array('group' => '[details_adv]'))
    ->editor('editor_teeny', 'Teeny Editor', array(), array('teeny' => true, 'media_buttons' => false))
    ->buffer('advanced');


  $screen = new acpt_options();
  $screen->add_tab( array(
      'id' => 'general',            //unique id for the tab
      'title' => "General",      //unique visible title for the tab
      'content' => $form->buffer['general']
    ) )
    ->add_tab( array(
        'id' => 'advanced',            //unique id for the tab
        'title' => "Advanced",      //unique visible title for the tab
        'content' => $form->buffer['advanced']  //actual help text
        //'callback' => 'acpt_textCallback' //optional function to callback
      ) )
    ->add_tab( array(
        'id' => 'help',            //unique id for the tab
        'title' => "Help",      //unique visible title for the tab
        'content' => '',  //actual help text
        'callback' => 'acpt_textCallback' //optional function to callback
      ) );

  $screen->set_sidebar('<input type="submit" value="Save Changes" class="button-primary" />');
  $screen->make();

  $form->end();
  ?>
  </div>
  <?php
}

function acpt_textCallback() {
  ?>
  This is some example text for the plugin using multiple tabs. This might be helpful to show how to add more than one tab.
<?php
}