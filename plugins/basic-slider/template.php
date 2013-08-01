<?php
// list functions
function list_slides($tax = false, $opts = array(), $settings = array()) {
// get slides
$width = $height = '';

$args = array(
'numberposts' => 10,
'order'=> 'ASC',
'post_status' => 'publish',
'orderby' => 'menu_order',
'post_type' => 'slide');

$force = array(
'post_type' => 'slide'
);

if(!is_array($opts)) $opts = array();
if(is_string($tax) && isset($tax)) :
$tax = array('tax_query' => array( array('taxonomy' => 'kind', 'field' => 'slug', 'terms' => $tax ) ) );
else :
$tax = array();
endif;

$args = array_merge($args, $opts, $tax, $force);

// setup html template
$setup = array(
'width' => '940',
'height' => '350',
'alt' => true,
'caption_classes' => 'caption',
'classes' => 'slides');
$settings = array_merge($setup, $settings);

if(isset($settings['width'])) $width = " width=\"{$settings['width']}\" ";
if(isset($settings['height'])) $height = " height=\"{$settings['height']}\" ";
if(isset($settings['caption_classes'])) :
$classCaption = $settings['caption_classes'];
else :
$classCaption = '';
endif;

if(isset($settings['classes'])) :
$classes = $settings['classes'];
else :
$classes = '';
endif;

// query and template
wp_reset_postdata();
$slides = new WP_Query($args); ?>
<ul class="<?php echo $classes; ?>">
<?php if ( $slides->have_posts() ) : while ( $slides->have_posts() ) : $slides->the_post(); ?>
    <li>
        <img src="<?php e_acpt_meta("acpt_slide_image"); ?>" <?php echo $width.$height ?> alt="<?php the_title(); ?>">

			<?php if (acpt_meta("acpt_slide_showText") == 'Yes') : ?>
        <div class="<?php echo $classCaption; ?>">
            <h2><?php e_acpt_meta("acpt_slide_headline"); ?></h2>
            <p><?php e_acpt_meta("acpt_slide_description"); ?></p>
        </div>
			<?php endif; ?>
    </li>
	<?php endwhile; endif; ?>
</ul>
<?php
wp_reset_postdata();

}

// short code [acpt_slider]
function acpt_slider_code( $attr ) {
    $group = false;
    $height = null;
    $width = null;
    $alt = null;
    $caption_classes = null;
    $classes = null;

    extract( shortcode_atts( array(
                'group' => false,
                'height' => '350',
                'width' => '940',
                'alt' => true,
                'caption_classes' => 'caption',
                'classes' => 'slides'
            ), $attr ) );

    $settings = array(
        'height' => $height,
        'width' => $width,
        'alt' => $alt,
        'caption_classes' => $caption_classes,
        'classes' => $classes);

    ob_start();
    list_slides($group, array(), $settings);
    $output = ob_get_clean( );

    return $output;
}

add_shortcode( 'acpt_slider', 'acpt_slider_code' );