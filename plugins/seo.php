<?php
if( !defined('WPSEO_URL') && !defined('AIOSEOP_VERSION') ) {
	add_action( 'add_meta_boxes', 'acpt_seo_meta' );
	add_filter( 'wp_title', 'acpt_seo_title', 100 );
	add_action( 'wp_head' , 'acpt_seo_head_data', 0);
  remove_action('wp_head', 'rel_canonical');
}

function acpt_seo_meta() {
	$publicTypes = get_post_types( array( 'public' => true ) );
	acpt_meta_box('acpt_seo', $publicTypes, array('label' => 'Search Engine Optimization'));
}

function meta_acpt_seo() {

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

  $form = acpt_form('acpt_seo', array('group' => '[seo][meta]'))
    ->buffer()
    ->text('title', array('label' => 'Page Title'))
    ->textarea('description', array('label' => 'Search Result Description'))
    ->buffer('general')
    ->buffer()
    ->textarea('og_desc', $og_desc)
    ->image('meta_img', $img)
    ->buffer('social')
    ->buffer()
    ->text('canonical', $canon)
    ->select('follow', array('Not Set' => false, 'Follow' => 'follow', 'Don\'t Follow' => 'nofollow'),array('label' => 'Following', 'select_key' => true, 'desc' => 'Don\'t Follow', 'help' => 'This instructs search engines not to follow links on this page. This only applies to links on this page. It\'s entirely likely that a robot might find the same links on some other page and still arrive at your undesired page.'))
    ->select('index', array('Not Set' => false, 'Index' => 'index', 'Don\'t Index' => 'noindex'), array('label' => 'Indexing', 'select_key' => true, 'desc' => 'Don\'t Index', 'help' => 'This instructs search engines not to show this page in its web search results.'))
    ->buffer('extra');

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

function acpt_seo_head_data() {
	global $post;
  $meta = acpt_meta('[seo][meta]');
	$desc = esc_attr($meta['acpt_seo_description']);
  $og_desc = esc_attr($meta['acpt_seo_og_desc']);
  $img = esc_attr($meta['acpt_seo_meta_img']);
  $canon = esc_attr($meta['acpt_seo_canonical']);
  $url = get_permalink($post->ID);
  $robots['index'] = esc_attr($meta['acpt_seo_index']);
  $robots['follow'] = esc_attr($meta['acpt_seo_follow']);

  // Extra
  if( !empty( $canon ) ) { echo "<link rel=\"canonical\" href=\"{$canon}\" />"; }
  else { echo "<link rel=\"canonical\" href=\"{$url}\" />"; }

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