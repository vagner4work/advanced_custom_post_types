<div class="wrap container profile-wrapper">
<?php
  $number = 0;
  $profile_url = acpt_profile::get_the_profile_page_url();

  extract(array(
    "number" => '10'
  ));

  $number = sanitize_text_field($number);
  $search = ( isset($_GET["as"]) ) ? sanitize_text_field($_GET["as"]) : false ;
  $sort = ( isset($_GET["sort"]) ) ? sanitize_text_field($_GET["sort"]) : false ;
  $order = ( isset($_GET["order"]) ) ? sanitize_text_field($_GET["order"]) : false ;
  $page = ($query_vars['paged']) ? $query_vars['paged'] : 1;
  $offset = ($page - 1) * $number;

  $args = array();

  if ($search){
    $args += array(
        'search' => '*' . $search . '*'
      );
  }
  else {
    $args += array(
        'offset' => $offset ,
        'number' => $number
      );
  }

  if($sort && $order) {
    if($sort == 'email') {
      $args += array(
        'order' => $order,
        'orderby' => 'email'
      );
    } else {
      $args += array(
        'order' => $order,
        'meta_key' => $sort,
        'orderby' => 'meta_value'
      );
    }
  }

  $my_users = new WP_User_Query($args);

  $total_authors = count_users();
  $total_authors = $total_authors['total_users'];

  $total_pages = intval($total_authors / $number) + 1;
  $authors = $my_users->get_results();
?>

  <div class="author-search">
    <form method="get" id="sul-searchform" action="<?php echo acpt_profile::get_the_profile_page_url() ?>">
      <label for="as" class="assistive-text">Search</label>
      <input type="text" class="field" name="as" id="sul-s" placeholder="Search for someone" />
      <input type="submit" class="submit" id="sul-searchsubmit" value="Search Authors" />
    </form>
  </div>

  <?php if($search) : ?>
    <h2>
      <a href="<?php echo $profile_url; ?>">Go Back</a> | Results for: <?php echo $search; ?>
    </h2>
  <?php else : ?>
    <h2>Team</h2>
  <?php endif; ?>

  <?php if (!empty($authors))	 { ?>

    <table class="author-list">

      <tr class="table-left ">
        <th></th>
        <th><a href="<?php echo $profile_url . acpt_profile::get_sort_query_vars('first_name'); ?>">First</a></th>
        <th><a href="<?php echo $profile_url . acpt_profile::get_sort_query_vars('last_name'); ?>">Last</a></th>
        <th><a href="<?php echo $profile_url . acpt_profile::get_sort_query_vars('email'); ?>">Email</a></th>
        <th><a href="<?php echo $profile_url . acpt_profile::get_sort_query_vars('title'); ?>">Title</a></th>
        <th><a href="<?php echo $profile_url . acpt_profile::get_sort_query_vars('location'); ?>">Location</a></th>
        <th><a href="<?php echo $profile_url . acpt_profile::get_sort_query_vars('department'); ?>">Department</a></th>
      </tr>

      <?php foreach($authors as $author) :
        acpt_current_user::setup($author->ID);
        $profile_id = acpt_current_user::get_data('id');
        $name = acpt_current_user::get_data('name');
        $user_meta = acpt_current_user::get_data('user_meta');
        $meta = acpt_current_user::get_data('meta');
        $role = acpt_current_user::get_data('role');
        $email = acpt_current_user::get_data('email');
        $img = get_avatar( $profile_id, 35 );
        ?>

        <tr>
          <td class="table-center"><a href="<?php echo acpt_profile::get_the_profile_url($profile_id); ?>"><?php echo $img;?></a></td>
          <td><?php echo $meta->first_name; ?></a></td>
          <td><?php echo $meta->last_name; ?></a></td>
          <td><?php echo $email; ?></td>
          <td><?php echo $user_meta['title'][0]; ?></td>
          <td><?php echo $user_meta['location'][0]; ?></td>
          <td><?php echo $user_meta['department'][0]; ?></td>
        </tr>

      <?php endforeach; ?>
    </table>
  <?php } ?>

  <?php if(!$search) { ?>
  <nav class="author-nav">
    <?php if ($page != 1) { ?>
      <span class="nav-previous"><a rel="prev" href="<?php echo acpt_profile::get_the_profile_page_url(); ?>page/<?php echo $page - 1; ?>/<?php echo acpt_profile::get_query_string(); ?>"><span class="meta-nav">←</span> Previous</a></span>
    <?php } ?>

    <?php if ($page < $total_pages ) { ?>
      <span class="nav-next"><a rel="next" href="<?php echo acpt_profile::get_the_profile_page_url(); ?>page/<?php echo $page + 1; ?>/<?php echo acpt_profile::get_query_string(); ?>">Next <span class="meta-nav">→</span></a></span>
    <?php } ?>
  </nav>
  <?php } ?>

</div>