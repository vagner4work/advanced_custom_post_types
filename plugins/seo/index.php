<?php
// add acpt seo to wordpress
if( !defined('WPSEO_URL') && !defined('AIOSEOP_VERSION') ) {
  define('ACPT_SEO', '2.0');
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

  $og_title = array(
    'label' => 'Title',
    'help' => 'The open graph protocol is used by social networks like FB, Google+ and Pinterest. Set the title used when sharing.'
  );

  $og_desc = array(
    'label' => 'Description',
    'help' => 'Set the open graph description to override "Search Result Description". Will be used by FB, Google+ and Pinterest.'
  );

  $img = array(
    'label' => 'Image',
    'help' => 'The image is shown when sharing socially using the open graph protocol. Will be used by FB, Google+ and Pinterest.'
  );

  $canon = array(
    'label' => 'Canonical URL',
    'help' => 'The canonical URL that this page should point to, leave empty to default to permalink.'
  );

  $redirect = array(
    'label' => '301 Redirect',
    'help' => 'Move this page permanently to a new URL. <a href="#acpt_acpt_seo_redirect" id="acpt_acpt_seo_redirect_lock">Unlock 301 Redirect</a>',
    'readonly' => true
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
      ->text('og_title', $og_title)
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
        'content' => $form->buffer['general'],
        'callback' => 'acpt_seo_general_cb'
      ) )
    ->add_tab( array(
        'id' => 'seo-social',
        'title' => "OG",
        'content' => $form->buffer['social']
      ) )
    ->add_tab( array(
        'id' => 'seo-extra',
        'title' => "Extras",
        'content' => $form->buffer['extra']
      ) )
    ->make('metabox');

}

function acpt_seo_general_cb() {
  global $post; ?>
  <div id="acpt-seo-preview" class="control-group">
    <h4>Example Preview</h4>
    <p>Google has <b>no definitive character limits</b> for page "Titles" and "Descriptions". Because of this there is no way to provide an accurate preview. But, your Google search result may look something like:</p>
    <div class="acpt-seo-preview-google">
      <span class="acpt-hide" id="acpt-seo-preview-google-title-orig">
        <?php echo substr($post->post_title, 0, 59); ?>
      </span>
      <span id="acpt-seo-preview-google-title">
        <?php
        $title = acpt_meta('[seo][meta][acpt_seo_title]');
        if(!empty($title)) {
          $tl = strlen($title);
          echo substr($title, 0, 59);
        } else {
          $tl = strlen($post->post_title);
          echo substr($post->post_title, 0, 59);
        }

        if($tl > 59) {
          echo '...';
        }
        ?>
      </span>
      <div id="acpt-seo-preview-google-url">
        <?php echo get_permalink($post->ID); ?>
      </div>
      <span class="acpt-hide" id="acpt-seo-preview-google-desc-orig">
        <?php echo substr($post->post_content, 0, 150); ?>
      </span>
      <span id="acpt-seo-preview-google-desc">
        <?php
        $desc = acpt_meta('[seo][meta][acpt_seo_description]');
        if(!empty($desc)) {
          $dl = strlen($desc);
          echo substr($desc, 0, 150);
        } else {
          $dl = strlen($post->post_content);
          echo substr($post->post_content, 0, 150);
        }

        if($dl > 150) {
          echo ' ...';
        }
        ?>
      </span>
    </div>
  </div>
<?php }

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
  $og_title = esc_attr(acpt_meta('[seo][meta][acpt_seo_og_title]'));
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
  if( !empty( $og_title ) ) { echo "<meta property=\"og:title\" content=\"{$og_title}\" />"; }
  if( !empty( $og_desc ) ) { echo "<meta property=\"og:description\" content=\"{$og_desc}\" />"; }
  if( !empty( $img ) ) { echo "<meta property=\"og:image\" content=\"{$img}\" />"; }

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

// CSS
function acpt_seo_css() {
  $path = acpt_utility::plugin_url('seo');
  wp_enqueue_style('acpt-seo', $path . 'style.css' );
  wp_enqueue_script('acpt-seo', $path . 'script.js' );
}

add_action('admin_init', 'acpt_seo_css');