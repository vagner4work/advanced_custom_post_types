<div class="wrap container profile-wrapper">

  <a class="profile-back" href="<?php echo acpt_profile::get_the_profile_page_url(); ?>">Back to Team</a>

  <div class="profile-content toggle-profile" style="display: none">
    <a href="#" class="toggle-btn">View Profile</a>
    <?php acpt_profile::the_form($profile_id); ?>
  </div>
  
  <?php
  $email = acpt_current_user::get_data('email');
  $gallery = acpt_current_user::get_data('gallery');
  $id = acpt_current_user::get_data('id');
  ?>

  <div class="profile-content toggle-profile row">

    <div class="avatar span3">
      <?php echo get_avatar( $profile_id, 150 ); ?>
    </div>
    <div class="information span9">
      <a href="#" class="toggle-btn">Edit Profile</a>
      <h2><?php echo acpt_current_user::get_data('name'); ?></h2>
      <h4><?php echo acpt_user_meta('[title]', $profile_id); ?></h4>
      <h4><a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></h4>
      <h4>m: <?php echo acpt_user_meta('[phone]', $profile_id); ?></h4>
      <h4>ext: <?php echo acpt_user_meta('[ext]', $profile_id); ?></h4>

      <div class="body">
        <?php echo nl2br(acpt_user_meta('[description]', $profile_id)); ?>
      </div>

      <?php if(is_array($gallery)) : ?>
        <div class="gallery">
        <h2>User Gallery</h2>
        <?php
        foreach($gallery as $k => $id) :
          $src = wp_get_attachment_image_src($id, 'full');
          echo "<a href=\"{$src[0]}\" class=\"gallery-image fancybox\" target=\"_blank\" rel=\"group\">";
          echo wp_get_attachment_image( $id, 'thumbnail' );
          echo '</a>';
        endforeach;
        ?>
        </div>
      <?php endif; ?>

    </div>

  </div>

  <a class="profile-back" href="<?php echo acpt_profile::get_the_profile_page_url(); ?>">Back to Team</a>

</div>