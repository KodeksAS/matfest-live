<div class="spilleplan">

  <?php
  $manually_set_active_date = get_field('plan_date_active', 'options');
  // Timeline range and scaling
  $acf_timeline_start = get_field('spilleplan_timeline_start', 'options');
  $timeline_start = $acf_timeline_start ? strtotime($acf_timeline_start) : strtotime('08:00');

  $acf_timeline_end = get_field('spilleplan_timeline_end', 'options');
  if ($acf_timeline_end) {

    // If end time is less than or equal to start, assume it's next day
    $timeline_end = strtotime($acf_timeline_end);
    if ($timeline_end <= $timeline_start) {
      $timeline_end = strtotime($acf_timeline_end . ' +1 day', $timeline_start);
    }
  } else {
    $timeline_end = strtotime('01:00 +1 day', $timeline_start); // fallback
  }

  $pixels_per_minute = 2;
  $timeline_minutes = ($timeline_end - $timeline_start) / 60;
  $grid_width = $timeline_minutes * $pixels_per_minute;

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
      $event_id = get_the_ID();

      if ($date && $start && $end) {
        $events_by_date[$date][] = [
          'stage' => $stage,
          'start' => $start,
          'end' => $end,
          'title' => $title,
          'id' => $event_id,
        ];
      }
    }
    wp_reset_postdata();
  }

  $all_locations = get_terms(['taxonomy' => 'stage_location', 'hide_empty' => false]);
  $location_names = wp_list_pluck($all_locations, 'name');

  uksort($events_by_date, function ($a, $b) {
    return strtotime($a) - strtotime($b);
  });

  // Filter out past dates (keep today and future)
  $today = strtotime('today');
  $future_events_by_date = array_filter($events_by_date, function($events, $date) use ($today) {
    return strtotime($date) >= $today;
  }, ARRAY_FILTER_USE_BOTH);

  // If all events are in the past, fallback to showing all
  if (empty($future_events_by_date)) {
    $future_events_by_date = $events_by_date;
  }
  // Check if manually set date matches a tab
  $default_date_attr = '';
  if ($manually_set_active_date && isset($future_events_by_date[$manually_set_active_date])) {
    $default_date_attr = ' data-default-date="' . esc_attr($manually_set_active_date) . '"';
  }
?>

  <div class="spilleplan-tabs"<?= $default_date_attr; ?>>
    <?php foreach ($future_events_by_date as $date => $events) : ?>
      <button class="spilleplan-tab" data-date="<?= esc_attr($date); ?>"><?= esc_html(date_i18n('l j. M', strtotime($date))); ?></button>
    <?php endforeach; ?>
  </div>

  <div class="spilleplan-wrapper" style="--spilleplan-grid-width: <?= esc_attr($grid_width); ?>px;">

    <?php
    // Use ACF fields for start and end hour, with fallback to 8 and 25
    $acf_timeline_start = get_field('spilleplan_timeline_start');
    $acf_timeline_end = get_field('spilleplan_timeline_end');

    $hour = $acf_timeline_start ? (int)date('G', strtotime($acf_timeline_start)) : 8;
    $end_hour = $acf_timeline_end
      ? (int)date('G', strtotime($acf_timeline_end)) + ((strtotime($acf_timeline_end) <= strtotime($acf_timeline_start)) ? 24 : 0)
      : 25; // fallback to 01:00 next day = hour 25
    ?>

    <div class="time-header">
      <?php $current_marker = $timeline_start;
      while ($current_marker <= $timeline_end) :
        $hour = (int)date('G', $current_marker);
        $time_label = str_pad($hour % 24, 2, '0', STR_PAD_LEFT) . ':00';
        $left = ($current_marker - $timeline_start) / 60 * $pixels_per_minute; ?>
        <div class="hour-marker" style="left: <?= $left; ?>px;"><?= esc_html($time_label); ?></div>
        <?php $current_marker = strtotime('+1 hour', $current_marker); ?>
      <?php endwhile; ?>
    </div>

    <div class="guidelines" style="width: <?= esc_attr($grid_width); ?>px;">
      <?php
      $current_marker = $timeline_start;
      while ($current_marker < $timeline_end) :
        $next_marker = strtotime('+1 hour', $current_marker);
        $left = ($current_marker - $timeline_start) / 60 * $pixels_per_minute;
        $next_left = ($next_marker - $timeline_start) / 60 * $pixels_per_minute;
        $width = $next_left - $left; ?>
        <div class="guideline" style="left: <?= $left; ?>px; width: <?= $width; ?>px;"></div>
      <?php
        $current_marker = $next_marker;
      endwhile;
      ?>
    </div>

    <div class="sp-grid" style="min-width: <?= esc_attr($grid_width); ?>px;">
      <?php foreach ($location_names as $location) : ?>
        <div class="sp-row">
          <div class="location">
            <?= esc_html($location); ?>
          </div>
          <div class="events">
            <?php foreach ($future_events_by_date as $date => $events) : ?>
              <?php foreach ($events as $event) :
                if ($event['stage'] !== $location) continue;

                $event_start = strtotime($event['start']);
                $event_end = strtotime($event['end']);

                // Handle events crossing midnight
                if ($event_end < $event_start) :
                  $event_end = strtotime($event['end'] . ' +1 day');
                endif;

                $left = ($event_start - $timeline_start) / 60 * $pixels_per_minute;
                $width = ($event_end - $event_start) / 60 * $pixels_per_minute;

                $event_duration = ($event_end - $event_start) / 60; // duration in minutes
                $is_short_event = $event_duration < 90;

                $thumb = get_the_post_thumbnail($event['id'], 'thumbnail');
                ?>

                <div class="sp-event <?= $is_short_event ? 'short-event' : ''; ?>" data-date="<?= esc_attr($date); ?>" style="left: <?= $left; ?>px; width: <?= $width; ?>px;">
                  <a href="<?= get_the_permalink($event['id']); ?>">

                    <?php if (!$is_short_event && $thumb): ?>
                      <div class="img-wrapper"><?= $thumb; ?></div>
                    <?php endif; ?>

                    <div class="text-wrapper">
                      <h4><?= esc_html($event['title']); ?></h4>
                      <span class="time"><?= esc_html($event['start']); ?>–<?= esc_html($event['end']); ?></span>
                    </div>
                    
                  </a>
                </div>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>