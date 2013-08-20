<?php
// add acpt seo to wordpress
if( !defined('WPSEO_URL') && !defined('AIOSEOP_VERSION') ) {
	add_action( 'add_meta_boxes', 'acpt_seo_meta' );
	add_filter( 'wp_title', 'acpt_seo_title', 100 );
	add_action( 'wp_head' , 'acpt_seo_head_data', 0);
  remove_action('wp_head', 'rel_canonical');
  add_action( 'wp', 'acpt_seo_redirect', 99, 1 );
}

// setup metabox
function acpt_seo_meta() {
	$publicTypes = get_post_types( array( 'public' => true ) );
	acpt_meta_box('acpt_seo', $publicTypes, array('label' => 'Search Engine Optimization'));
}

// build metabox interface
function meta_acpt_seo() {

  // field settings
  $title = array(
    'label' => 'Page Title'
  );

  $desc = array(
    'label' => 'Search Result Description'
  );

  $og_desc = array(
    'label' => 'Description',
    'help' => 'Use <a href="http://ogp.me/" target="_blank">open graph protocol</a> to set Facebook and Google+ descriptions to override "Search Result Description".'
  );

  $img = array(
    'label' => 'Image',
    'help' => 'The image is shown when sharing socially using the open graph protocol.'
  );

  $canon = array(
    'label' => 'Canonical URL',
    'help' => 'The canonical URL that this page should point to, leave empty to default to permalink.'
  );

  $redirect = array(
    'label' => '301 Redirect',
    'help' => 'Move this page permanently to a new URL.'
  );

  $follow = array(
    'label' => 'Robots Follow?',
    'select_key' => true,
    'desc' => "Don't Follow",
    'help' => 'This instructs search engines not to follow links on this page. This only applies to links on this page. It\'s entirely likely that a robot might find the same links on some other page and still arrive at your undesired page.'
  );

  $help = array(
    'label' => 'Robots Index?',
    'select_key' => true,
    'desc' => "Don't Index",
    'help' => 'This instructs search engines not to show this page in its web search results.'
  );

  // select options
  $follow_opts = array(
    'Not Set' => false,
    'Follow' => 'follow',
    "Don't Follow" => 'nofollow'
  );

  $index_opts = array(
    'Not Set' => false,
    'Index' => 'index',
    "Don't Index" => 'noindex'
  );

  // build form
  $form = acpt_form('acpt_seo', array('group' => '[seo][meta]'))
    ->buffer()
      ->text('title', $title)
      ->textarea('description', $desc)
    ->buffer('general') // index buffer
    ->buffer()
      ->textarea('og_desc', $og_desc)
      ->image('meta_img', $img)
    ->buffer('social') // index buffer
    ->buffer()
      ->text('canonical', $canon)
      ->text('redirect', $redirect)
      ->select('follow', $follow_opts, $follow)
      ->select('index', $index_opts, $help)
    ->buffer('extra'); // index buffer

  $tabs = new acpt_layout();
  $tabs
    ->add_tab( array(
        'id' => 'seo-general',
        'title' => "Basic",
        'content' => $form->buffer['general']
      ) )
    ->add_tab( array(
        'id' => 'seo-social',
        'title' => "Open Graph",
        'content' => $form->buffer['social']
      ) )
    ->add_tab( array(
        'id' => 'seo-extra',
        'title' => "Extras",
        'content' => $form->buffer['extra']
      ) )
    ->make('metabox');

}

// Page Title
function acpt_seo_title( $title, $sep = '', $other = '' ) {
    global $paged, $page;

    $newTitle = acpt_meta('[seo][meta][acpt_seo_title]');

    if ( $newTitle != '') {
      if(is_feed() || is_single() || is_page() || is_singular() ) {
        return $newTitle;
      } else {
        return $title;
      }
    } else {
      return $title;
    }

}

// head meta data
function acpt_seo_head_data() {
	global $post;

  // meta vars
	$desc = esc_attr(acpt_meta('[seo][meta][acpt_seo_description]'));
  $og_desc = esc_attr(acpt_meta('[seo][meta][acpt_seo_og_desc]'));
  $img = esc_attr(acpt_meta('[seo][meta][acpt_seo_meta_img]'));
  $canon = esc_attr(acpt_meta('[seo][meta][acpt_seo_canonical]'));
  $robots['index'] = esc_attr(acpt_meta('[seo][meta][acpt_seo_index]'));
  $robots['follow'] = esc_attr(acpt_meta('[seo][meta][acpt_seo_follow]'));

  // Extra
  if( !empty( $canon ) ) { echo "<link rel=\"canonical\" href=\"{$canon}\" />"; }
  else { rel_canonical(); }

  // Robots
  if( !empty( $robots ) ) {
    $robot_data = '';
    foreach($robots as $key => $value) {
      if(is_string($value)) {
        $robot_data = $value . ', ';
      }
    }

    $robot_data = substr($robot_data, 0, -2);
    if(!empty($robot_data)) { echo "<link name=\"robots\" content=\"{$robot_data}\" />"; }
  }

  // OG
  if( !empty( $img ) ) { echo "<meta property=\"og:image\" content=\"{$img}\" />"; }
  if( !empty( $og_desc ) ) { echo "<meta property=\"og:description\" content=\"{$og_desc}\" />"; }

  // Basic
	if( !empty( $desc ) ) { echo "<meta name=\"Description\" content=\"{$desc}\" />"; }
}

// 301 Redirect
function acpt_seo_redirect() {
  if ( is_singular() ) {
    $redirect = acpt_meta('[seo][meta][acpt_seo_redirect]');
    if ( !empty( $redirect ) ) {
      wp_redirect( $redirect, 301 );
      exit;
    }
  }
}