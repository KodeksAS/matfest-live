/**
 * Shortcode: [spilleplan]
 * Usage: Add this shortcode to any post or page
 */

function spilleplan_shortcode()
{
  // Timeline range and scaling
  $timeline_start = strtotime('08:00');
  $timeline_end = strtotime('01:00 +1 day'); // 1am the next day
  $pixels_per_minute = 2;
  $timeline_minutes = ($timeline_end - $timeline_start) / 60;
  $grid_width = $timeline_minutes * $pixels_per_minute;

  // Query arrangements
  $args = [
    'post_type' => 'arrangement',
    'posts_per_page' => -1,
    'meta_key' => 'plan_start_time',
    'orderby' => 'meta_value',
    'order' => 'ASC',
  ];

  $query = new WP_Query($args);
  $events_by_date = [];

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $stage_terms = get_the_terms(get_the_ID(), 'stage_location');
      $stage = $stage_terms && !is_wp_error($stage_terms) ? $stage_terms[0]->name : 'Unknown';

      $date = get_field('plan_date');
      $start = date('H:i', strtotime(get_field('plan_start_time')));
      $end = date('H:i', strtotime(get_field('plan_end_time')));
      $title = get_the_title();

      if ($date && $start && $end) {
        $events_by_date[$date][] = [
          'stage' => $stage,
          'start' => $start,
          'end' => $end,
          'title' => $title,
        ];
      }
    }
    wp_reset_postdata();
  }

  $all_locations = get_terms(['taxonomy' => 'stage_location', 'hide_empty' => false]);
  $location_names = wp_list_pluck($all_locations, 'name');

  ob_start();

  echo '<div class="spilleplan-wrapper">';
  echo '<div class="spilleplan-tabs">';
  foreach ($events_by_date as $date => $events) {
    echo '<button class="spilleplan-tab" data-date="' . esc_attr($date) . '">' . esc_html(date('l j. F', strtotime($date))) . '</button>';
  }
  echo '</div>';

  foreach ($events_by_date as $date => $events) {

    // Time header row
    echo '<div class="spilleplan-time-header">';
    $hour = 8;
    $end_hour = 25; // 01:00 next day = hour 25

    while ($hour <= $end_hour) {
      $time_label = str_pad($hour % 24, 2, '0', STR_PAD_LEFT) . ':00';
      $left = ($hour - 8) * 60 * $pixels_per_minute; // offset from 08:00
      echo '<div class="spilleplan-hour-marker" style="left: ' . $left . 'px;">' . esc_html($time_label) . '</div>';
      $hour++;
    }
    echo '</div>';

    echo '<div class="spilleplan-grid" data-date="' . esc_attr($date) . '" style="display: none; min-width: ' . esc_attr($grid_width) . 'px;">';

    foreach ($location_names as $location) {
      echo '<div class="spilleplan-row">';
      echo '<div class="spilleplan-location">' . esc_html($location) . '</div>';
      echo '<div class="spilleplan-events">';

      foreach ($events as $event) {
        if ($event['stage'] !== $location) continue;

        $event_start = strtotime($event['start']);
        $event_end = strtotime($event['end']);

        // Handle events crossing midnight
        if ($event_end < $event_start) {
          $event_end = strtotime($event['end'] . ' +1 day');
        }

        $left = ($event_start - $timeline_start) / 60 * $pixels_per_minute;
        $width = ($event_end - $event_start) / 60 * $pixels_per_minute;

        echo '<div class="spilleplan-event" style="left: ' . $left . 'px; width: ' . $width . 'px">';
        echo esc_html($event['titles']) . '<br><small>' . esc_html($event['start'] . ' - ' . $event['end']) . '</small>';
        echo '</div>';
      }

      echo '</div></div>';
    }

    echo '</div>';
  }

  echo '</div>';

  return ob_get_clean();
}
add_shortcode('spilleplan', 'spilleplan_shortcode');



function enqueue_spilleplan_styles()
{
  global $post;

  if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'spilleplan')) {
    
    // Enqueue CSS
    wp_enqueue_style( 'spilleplan', get_stylesheet_directory_uri() . '/css/spilleplan.css', [], filemtime(get_stylesheet_directory_uri() . '/css/spilleplan.css') );

    // Enqueue JS (footer TRUE)
    wp_enqueue_script( 'spilleplan', get_stylesheet_directory_uri() . '/js/spilleplan.js', [], filemtime(get_stylesheet_directory_uri() . '/js/spilleplan.js'), true);
  }
}
add_action('wp_enqueue_scripts', 'enqueue_spilleplan_styles');