<?php
acpt_utility::upload_scripts();
acpt_current_user::setup((int) $profile_id);
$profile_id = acpt_current_user::$id;
$editor = current_user_can($profile_id) || current_user_can('edit_users');

// Start Form
if($editor) :

  echo "<div class=\"user-profile-{$profile_id}\">";

  $form = new acpt_form();
  $form->user = $profile_id;
  $form->connect = 'users';
  $form->advanced = true;
  $form->make('profile', array('method' => true), true, 'user_meta');
  acpt_current_user::setup((int) $profile_id);

  // Notifications
  $form->notice();

  echo '<div class="row"><div class="span3">';
  // Edit Basic Info
  $form
    ->user_firstname('First Name')
    ->user_lastname('Last Name')
    ->text('Phone', array('placeholder' => 'Phone'))
    ->text('ext', array('placeholder' => 'ext.'))
    ->text('title', array('label' => 'Title', 'placeholder' => 'Title'));
echo '</div><div class="span3">';
  $form
    ->text('Location', array('placeholder' => 'City, State'))
    ->text('Department', array('placeholder' => 'Department'))
    ->text('nickname', array('label' => 'Nickname', 'placeholder' => 'Nickname'))
    ->textarea('description', array('label' => 'Description', 'placeholder' => 'Description'));
  echo '</div><div class="span3">';
  $form
    ->text('google_profile', array('label' => 'Google Profile', 'placeholder' => 'Google Profile'))
    ->text('user_tw', array('label' => 'Twitter', 'placeholder' => 'Twitter'))
    ->text('user_fb', array('label' => 'Facebook', 'placeholder' => 'Facebook'))
    ->text('user_url', array('label' => 'Website', 'placeholder' => 'Website'));
  echo '</div><div class="span3">';
  $form
    ->user_email('Email')
    ->user_pass('Password');
  echo "</div></div><div class=\"profile-gallery row\"><div class=\"span4\">";
  $form
    ->image_repeater('User Gallery');
  echo "</div><div class=\"span4\">";
  $form
    ->image('Profile Avatar');
  echo "</div></div></div>";

endif;

// End Form
if($form) :
  $form->end('Update Profile');
else :
  echo "</div>";
endif;