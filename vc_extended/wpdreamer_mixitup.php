<?php

/**
 * Portfolio Shortcode
 */

if (!function_exists('movedo_ext_vce_get_posttype_categories')) {

  function movedo_ext_vce_get_posttype_categories($tax)
  {

    $portfolio_category = array(esc_html__("All Categories", "movedo-extension") => "");

    $portfolio_cats = get_terms($tax);
    if (is_array($portfolio_cats)) {
      foreach ($portfolio_cats as $portfolio_cat) {
        $portfolio_category[$portfolio_cat->name] = $portfolio_cat->term_id;
      }
    }
    return $portfolio_category;
  }
}

if (!function_exists('get_field_choices')) {
  function get_field_choices($field_name, $multi = false)
  {
    $results = array();
    foreach (get_posts(array('post_type' => 'acf-field', 'posts_per_page' => -1)) as $acf) {
      if ($acf->post_excerpt === $field_name) {
        $field = unserialize($acf->post_content);
        if (isset($field['choices'])) {
          if (!$multi) return $field['choices'];
          else $results[] = $field;
        }
      }
    }
    return $results;
  }
}

if (!function_exists('movedo_ext_vce_mixitup_shortcode')) {

  function movedo_ext_vce_mixitup_shortcode($attr, $content)
  {

    $portfolio_row_start = $allow_filter = $class_fullwidth = $el_class = '';
    $combined_atts = $attr;

    extract(
      $combined_atts = shortcode_atts(
        array(
          'title' => '',
          'text_style' => 'none',
          'align' => 'left',
          'categories' => '',
          'exclude_posts' => '',
          'include_posts' => '',
          'portfolio_mode' => 'grid',
          'carousel_layout' => 'layout-1',
          'heading_tag' => 'h3',
          'heading' => 'h3',
          'custom_font_family' => '',
          'columns_large_screen' => '3',
          'columns' => '3',
          'columns_tablet_landscape' => '2',
          'columns_tablet_portrait' => '2',
          'columns_mobile' => '1',
          'grid_image_mode' => 'landscape',
          'masonry_image_mode' => '',
          'carousel_image_mode' => 'landscape',
          'portfolio_link_type' => 'item',
          'image_popup_size' => 'extra-extra-large',
          'portfolio_overview_type' => '',
          'portfolio_filter' => '',
          'filter_style' => 'simple',
          'filter_shape' => 'square',
          'filter_color' => 'primary-1',
          'portfolio_filter_align' => 'left',
          'filter_order_by' => '',
          'filter_order' => 'ASC',
          'filter_gototop' => 'yes',
          'item_gutter' => 'yes',
          'gutter_size' => '30',
          'item_spinner' => 'no',
          'items_per_page' => '4',
          'items_tablet_landscape' => '3',
          'items_tablet_portrait' => '3',
          'items_mobile' => '1',
          'items_to_show' => '12',
          'hide_portfolio_like' => '',
          'portfolio_title_caption' => 'title-caption',
          'portfolio_title_heading_tag' => 'h3',
          'portfolio_title_heading' => 'h3',
          'heading_auto_size' => 'no',
          'portfolio_style' => 'hover-style-1',
          'content_bg_color' => 'white',
          'zoom_effect' => 'none',
          'grayscale_effect' => 'none',
          'overlay_color' => 'light',
          'overlay_opacity' => '90',
          'order_by' => 'date',
          'order' => 'DESC',
          'disable_pagination' => '',
          'slideshow_speed' => '3000',
          'auto_play' => 'yes',
          'loop' => 'yes',
          'navigation_type' => '1',
          'navigation_color' => 'dark',
          'pause_hover' => 'no',
          'carousel_pagination' => 'no',
          'carousel_pagination_speed' => '400',
          'animation' => 'grve-zoom-in',
          'margin_bottom' => '',
          'el_class' => '',
        ),
        $attr
      )
    );

    $portfolio_classes = array('grve-element');
    $data_string = '';

    switch ($portfolio_mode) {
      case 'carousel':
        $data_string .= ' data-items="' . esc_attr($items_per_page) . '"';
        $data_string .= ' data-items-tablet-landscape="' . esc_attr($items_tablet_landscape) . '"';
        $data_string .= ' data-items-tablet-portrait="' . esc_attr($items_tablet_portrait) . '"';
        $data_string .= ' data-items-mobile="' . esc_attr($items_mobile) . '"';
        $data_string .= ' data-slider-autoplay="' . esc_attr($auto_play) . '"';
        $data_string .= ' data-slider-speed="' . esc_attr($slideshow_speed) . '"';
        $data_string .= ' data-slider-pause="' . esc_attr($pause_hover) . '"';
        $data_string .= ' data-pagination-speed="' . esc_attr($carousel_pagination_speed) . '"';
        $data_string .= ' data-pagination="' . esc_attr($carousel_pagination) . '"';
        $data_string .= ' data-slider-loop="' . esc_attr($loop) . '"';
        if ('yes' == $item_gutter) {
          $data_string .= ' data-gutter-size="' . esc_attr($gutter_size) . '"';
        }
        array_push($portfolio_classes, 'grve-carousel');
        array_push($portfolio_classes, 'grve-' . $carousel_layout);
        if ('popup' == $portfolio_link_type) {
          array_push($portfolio_classes, 'grve-gallery-popup');
        }

        if ('yes' == $item_gutter) {
          array_push($portfolio_classes, 'grve-with-gap');
        }
        $disable_pagination = 'yes';
        break;
      case 'masonry':
        $portfolio_row_start = '<div class="grve-isotope-container mixitup-container">';
        if ('popup' == $portfolio_link_type) {
          $portfolio_row_start = '<div class="grve-isotope-container mixitup-container grve-gallery-popup">';
        }
        $data_string = ' data-spinner="' . esc_attr($item_spinner) . '" data-columns="' . esc_attr($columns) . '" data-columns-large-screen="' . esc_attr($columns_large_screen) . '" data-columns-tablet-landscape="' . esc_attr($columns_tablet_landscape) . '" data-columns-tablet-portrait="' . esc_attr($columns_tablet_portrait) . '" data-columns-mobile="' . esc_attr($columns_mobile) . '" data-layout="masonry"';
        if ('yes' == $item_gutter) {
          $data_string .= ' data-gutter-size="' . esc_attr($gutter_size) . '"';
        }
        if ('yes' == $item_gutter) {
          array_push($portfolio_classes, 'grve-with-gap');
        }
        if ('yes' == $heading_auto_size) {
          array_push($portfolio_classes, 'grve-auto-headings');
        }
        array_push($portfolio_classes, 'grve-portfolio');
        array_push($portfolio_classes, 'grve-isotope');
        $allow_filter = 'yes';
        break;
      case 'grid':
      default:
        $portfolio_row_start = '<div class="mixitup-container">';
        if ('popup' == $portfolio_link_type) {
          $portfolio_row_start = '<div class="mixitup-container grve-gallery-popup">';
        }
        $data_string = ' data-spinner="' . esc_attr($item_spinner) . '" data-columns="' . esc_attr($columns) . '" data-columns-large-screen="' . esc_attr($columns_large_screen) . '" data-columns-tablet-landscape="' . esc_attr($columns_tablet_landscape) . '" data-columns-tablet-portrait="' . esc_attr($columns_tablet_portrait) . '" data-columns-mobile="' . esc_attr($columns_mobile) . '" data-layout="fitRows"';
        if ('yes' == $item_gutter) {
          $data_string .= ' data-gutter-size="' . esc_attr($gutter_size) . '"';
        }
        if ('yes' == $item_gutter) {
          array_push($portfolio_classes, 'grve-with-gap');
        }
        array_push($portfolio_classes, 'grve-portfolio');
        array_push($portfolio_classes, 'grve-isotope');
        $allow_filter = 'yes';
        break;
    }

    $isotope_inner_item_classes = array('grve-isotope-item-inner', 'grve-hover-item');

    if (!empty($animation)) {
      //array_push( $isotope_inner_item_classes, $animation);
    }

    if ('none' == $portfolio_title_caption) {
      $portfolio_style = 'hover-style-none';
      array_push($isotope_inner_item_classes, 'grve-hover-style-none');
    } else {
      array_push($isotope_inner_item_classes, 'grve-' . $portfolio_style);
    }

    $isotope_inner_item_class_string = implode(' ', $isotope_inner_item_classes);

    // Image Effect
    $image_effect_classes = array('grve-image-hover', 'grve-media');
    if ('none' != $zoom_effect) {
      array_push($image_effect_classes, 'grve-zoom-' . $zoom_effect);
    }
    if ('none' != $grayscale_effect) {
      array_push($image_effect_classes, 'grve-' . $grayscale_effect);
    }
    $image_effect_class_string = implode(' ', $image_effect_classes);


    $image_popup_size_mode = 'extra-extra-large';
    if ('popup' == $portfolio_link_type) {
      $image_popup_size_mode = movedo_ext_vce_get_image_size($image_popup_size);
    }

    if (!empty($el_class)) {
      array_push($portfolio_classes, $el_class);
    }
    $portfolio_class_string = implode(' ', $portfolio_classes);

    $style = movedo_ext_vce_build_margin_bottom_style($margin_bottom);

    $portfolio_cat = "";
    $portfolio_category_ids = array();

    if (! empty($categories)) {
      $portfolio_category_ids = explode(",", $categories);
      foreach ($portfolio_category_ids as $category_id) {
        $category_term = get_term($category_id, 'arr-category');
        if (isset($category_term)) {
          $portfolio_cat = $portfolio_cat . $category_term->slug . ', ';
        }
      }
    }

    $paged = 1;

    if ('yes' != $disable_pagination) {
      if (get_query_var('paged')) {
        $paged = get_query_var('paged');
      } elseif (get_query_var('page')) {
        $paged = get_query_var('page');
      }
    }

    $exclude_ids = array();
    if (!empty($exclude_posts)) {
      $exclude_ids = explode(',', $exclude_posts);
    }

    $include_ids = array();
    if (!empty($include_posts)) {
      $include_ids = explode(',', $include_posts);
      $args = array(
        'post_type' => 'arrangement',
        'post_status' => 'publish',
        'paged' => $paged,
        'post__in' => $include_ids,
        'posts_per_page' => $items_to_show,
        'orderby' => $order_by,
        'order' => $order,
      );
      $portfolio_filter = 'no';
    } else {
      $args = array(
        'post_type' => 'arrangement',
        'post_status' => 'publish',
        'paged' => $paged,
        'arr-category' => $portfolio_cat,
        'post__not_in' => $exclude_ids,
        'posts_per_page' => $items_to_show,
        'orderby' => $order_by,
        'order' => $order,
      );
    }
    $args = apply_filters('movedo_grve_vce_portfolio_args', $args, $combined_atts);

    $query = new WP_Query($args);
    ob_start();
    if ($query->have_posts()) :
?>
      <div class="program">
        <div class="filter" style="background:#f0f0f0; padding:30px 20px;">
          <div class="grid side-padding filter-container">
            <form>
              <?php
              $tax = 'arr-category';
              $terms = get_terms([
                'taxonomy' => $tax,
                'hide_empty' => true,

              ]);
              if (count($terms) > 0) {
              ?>
                <fieldset data-filter-group="">
                  <div class="button-grid">
                    <button class="mixitup-control-active">Arrangement: </button>
                    <?php

                    foreach ($terms as $term) {
                    ?>

                      <button type='button' data-toggle='.<?php echo $tax . '-' . $term->slug; ?>'> <?php echo $term->name; ?></button>
                    <?php
                    }
                    ?>
                    <?php /*?><button class="portfolio_category-reset" type="reset">Nullstill</button><?php */ ?>
                  </div>
                </fieldset>
              <?php } ?>


              <?php

              $acf_fields = ['dag' => 'Dag'];
              foreach ($acf_fields as $key => $field) {
                $choices = get_field_choices($key);
                if (count($choices)) {
              ?>

                  <fieldset data-filter-group>
                    <div class="button-grid">
                      <button class="mixitup-control-active"><?php echo $field; ?>: </button>
                      <?php


                      foreach ($choices as $choice) {
                      ?>
                        <button type='button' data-toggle='.<?php echo $key . '_' . strtolower($choice); ?>'> <?php echo $choice; ?></button>
                      <?php
                      }
                      ?>

                      <?php /*?> <button class="day-reset" type="reset">Nullstill</button><?php */ ?>
                    </div>
                  </fieldset>

              <?php }
              } ?>

              <?php
              $acf_fields = ['aktor' => 'Aktør'];
              foreach ($acf_fields as $key => $field) {
              ?>
                <?php /*?> <form>
                <fieldset data-filter-group>
                    <div class="dropdown-wrapper">
                        <div class="custom-select mixitup-control-active">
                            <span class="icon-arrow"></span>
                            <select>
                                <option class="s-one" value=""><?php echo $field;?></option>

                            <?php 
                                $choices = get_field_choices($key);

                                foreach($choices as $choice){
                                ?>
                                <option value='.<?php echo $key.'_'.strtolower($choice);?>'><?php echo $choice;?></option>
                                <?php }?>
                            </select><?php */ ?>
                <!--<button class="portfolio_category-reset" type="reset">Alle!</button>-->
          </div>
          <!--<div class="custom-select">
                            <span class="icon-arrow"></span>
                            <select>
                                <option class="s-two" value="">Aktør</option>
                                <option value=gladmat>Gladmat (torget)</option><option value=gladmat-kokepunktet>Gladmat (Kokepunktet)</option><option value=a-idsoe>A.Idsøe</option><option value=iddis-cafe-brasserie>IDDIS Café og Brasserie</option><option value=stavanger-turistforening>Stavanger Turistforening (Gramstad)</option><option value=pedersgata>Pedersgata</option><option value=guidecompaniet>GuideCompaniet</option><option value=malt-humle-gladmat>Gladmat (Malt &#038; humle)</option><option value=ostehuset-ost>Ostehuset øst</option><option value=fish-cow>Fish and Cow</option><option value=stavanger-turistforening-kannik>Stavanger Turistforening (Kannik)</option>                            </select>
                        </div>
                        <button style="margin: 0;padding: 0;width: 1px; height: 1px; background: transparent;" class="dropdown-reset" type="reset"></button>
                                -->
          <form>
            <fieldset data-filter-group>
              <div class="dropdown-wrapper">
                <div class="search-wrapper">
                  <input type="text" class="input-search" data-ref="input-search" placeholder="Søk" />
                  <button class="portfolio_category-reset reset-all" type="reset">Nullstill</button>
                </div>
              </div>
            </fieldset>

        </div>
        </fieldset>



      <?php } ?>

      <!--<form>
                <fieldset data-filter-group>
                    <div class="dropdown-wrapper">
                        <div class="custom-select">
                            <span class="icon-arrow"></span>
                            <select>
                                <option class="s-one" value="">Område</option>

                                <option value='.gramstad'>Gramstad</option><option value='.kokepunktet'>Kokepunktet</option><option value='.stavanger-ost'>Stavanger øst</option><option value='.stavanger-sentrum'>Stavanger sentrum</option>
                            </select>
                        </div>
                        <div class="custom-select">
                            <span class="icon-arrow"></span>
                            <select>
                                <option class="s-two" value="">Aktør</option>
                                <option value=gladmat>Gladmat (torget)</option><option value=gladmat-kokepunktet>Gladmat (Kokepunktet)</option><option value=a-idsoe>A.Idsøe</option><option value=iddis-cafe-brasserie>IDDIS Café og Brasserie</option><option value=stavanger-turistforening>Stavanger Turistforening (Gramstad)</option><option value=pedersgata>Pedersgata</option><option value=guidecompaniet>GuideCompaniet</option><option value=malt-humle-gladmat>Gladmat (Malt &#038; humle)</option><option value=ostehuset-ost>Ostehuset øst</option><option value=fish-cow>Fish and Cow</option><option value=stavanger-turistforening-kannik>Stavanger Turistforening (Kannik)</option>                            </select>
                        </div>
                        <button style="margin: 0;padding: 0;width: 1px; height: 1px; background: transparent;" class="dropdown-reset" type="reset"></button>
                    </div>
                </fieldset>
            </form>
            <form>
                <fieldset data-filter-group>
                    <div class="show-results-wrapper">
                        <a href="#after-filterr" class="show-results">Vis resultater</a>
                        <a class="reset-all">Nullstill Søk</a>
                    </div>
                </fieldset>
            </form>-->

      </form>
      </div>
      </div>
      <div class="<?php echo esc_attr($portfolio_class_string); ?>" style="<?php echo $style; ?>" <?php echo $data_string; ?>>
        <?php

        if ('yes' == $portfolio_filter && 'yes' == $allow_filter) {

          $filter_classes = array('grve-filter');

          array_push($filter_classes, 'grve-filter-style-' . $filter_style);
          array_push($filter_classes, 'grve-align-' . $portfolio_filter_align);
          array_push($filter_classes, 'grve-link-text');

          if ('button' == $filter_style) {
            array_push($filter_classes, 'grve-link-text');
            array_push($filter_classes, 'grve-filter-shape-' . $filter_shape);
            array_push($filter_classes, 'grve-filter-color-' . $filter_color);
          }

          $filter_class_string = implode(' ', $filter_classes);


          $category_prefix = '.portfolio_category-';
          $category_filter_list = array();
          $category_filter_array = array();
          $all_string =  apply_filters('movedo_grve_vce_portfolio_string_all_categories', esc_html__('All', 'movedo-extension'));
          $category_filter_string = '<li data-filter="*" class="selected"><span>' . esc_html($all_string) . '</span></li>';
          $category_filter_add = false;
          while ($query->have_posts()) : $query->the_post();
            $post_id = get_the_ID();

            if ($portfolio_categories = get_the_terms($post_id, 'arr-category')) {

              foreach ($portfolio_categories as $category_term) {
                $category_filter_add = false;
                if (!in_array($category_term->term_id, $category_filter_list)) {
                  if (! empty($portfolio_category_ids)) {
                    if (in_array($category_term->term_id, $portfolio_category_ids)) {
                      $category_filter_add = true;
                    }
                  } else {
                    $category_filter_add = true;
                  }
                  if ($category_filter_add) {
                    $category_filter_list[] = $category_term->term_id;
                    if ('title' == $filter_order_by) {
                      $category_filter_array[$category_term->name] = $category_term;
                    } elseif ('slug' == $filter_order_by) {
                      $category_filter_array[$category_term->slug] = $category_term;
                    } else {
                      $category_filter_array[$category_term->term_id] = $category_term;
                    }
                  }
                }
              }
            }

          endwhile;


          if (count($category_filter_array) > 1) {
            if ('' != $filter_order_by) {
              if ('ASC' == $filter_order) {
                ksort($category_filter_array);
              } else {
                krsort($category_filter_array);
              }
            }
            foreach ($category_filter_array as $category_filter) {
              $term_class = sanitize_html_class($category_filter->slug, $category_filter->term_id);
              if (is_numeric($term_class) || ! trim($term_class, '-')) {
                $term_class = $category_filter->term_id;
              }
              $category_filter_string .= '<li data-filter="' . $category_prefix . $term_class . '"><span>' . $category_filter->name . '</span></li>';
            }

        ?>
            <div class="<?php echo esc_attr($filter_class_string); ?>" data-gototop="<?php echo esc_attr($filter_gototop); ?>">
              <ul>
                <?php echo $category_filter_string; ?>
              </ul>
            </div>
        <?php
          }
        }
        ?>

        <?php echo $portfolio_row_start; ?>

        <?php

        if ('carousel' == $portfolio_mode) {

          //Carousel Navigation
          if ('layout-2' == $carousel_layout) {
            echo '<div class="grve-carousel-info-wrapper grve-align-' . esc_attr($align) . '">';
            echo '  <div class="grve-carousel-info">';
            if (!empty($title)) {
              $title_classes = array('grve-title');
              $title_classes[]  = 'grve-' . $heading;
              if (!empty($custom_font_family)) {
                $title_classes[]  = 'grve-' . $custom_font_family;
              }
              $title_class_string = implode(' ', $title_classes);
              echo '    <' . tag_escape($heading_tag) . ' class="' . esc_attr($title_class_string) . '">' . $title . '</' . tag_escape($heading_tag) . '>';
            }
            if (!empty($content)) {
              echo '    <p class="grve-description grve-' . esc_attr($text_style) . '">' . movedo_ext_vce_unautop($content) . '</p>';
            }
            echo '  </div>';
            echo movedo_ext_vce_element_navigation($navigation_type, $navigation_color, 'carousel');
            echo '</div>';
          }
          echo '  <div class="grve-carousel-wrapper">';
          if ('layout-1' == $carousel_layout) {
            echo movedo_ext_vce_element_navigation($navigation_type, $navigation_color, 'carousel');
          }
        ?>
          <div class="grve-carousel-element grve-portfolio" <?php echo $data_string; ?>>
            <?php
          }

          $portfolio_index = 0;

          while ($query->have_posts()) : $query->the_post();
            $image_size = 'movedo-grve-small-rect-horizontal';
            $portfolio_index++;
            $portfolio_extra_class = '';
            $post_id = get_the_ID();



            $caption = get_post_meta($post_id, '_movedo_grve_description', true);
            $link_mode = get_post_meta($post_id, '_movedo_grve_portfolio_link_mode', true);
            $link_url = get_post_meta($post_id, '_movedo_grve_portfolio_link_url', true);
            $new_window = get_post_meta($post_id, '_movedo_grve_portfolio_link_new_window', true);
            $link_class = get_post_meta($post_id, '_movedo_grve_portfolio_link_extra_class', true);

            //Check Title and Caption
            $show_title = $show_caption = $show_title_or_caption = 'no';
            if ('none' != $portfolio_title_caption && 'caption-only' != $portfolio_title_caption) {
              $show_title = $show_title_or_caption = 'yes';
            }
            if ('none' != $portfolio_title_caption && 'title-only' != $portfolio_title_caption) {
              if (!empty($caption)) {
                $show_caption = 'yes';
              }
              $show_title_or_caption = 'yes';
            }

            if ('no' == $show_title_or_caption) {
              $portfolio_style = 'hover-style-none';
            }

            if ('carousel' != $portfolio_mode) {
              $image_size = 'movedo-grve-small-square';
              $portfolio_extra_class = 'mix grve-isotope-item grve-portfolio-item ';

              if ('masonry' == $portfolio_mode) {
                //Masonry
                if ('resize' == $masonry_image_mode || 'large' == $masonry_image_mode) {
                  $portfolio_extra_class .= 'grve-image-square';
                  $image_size = 'large';
                } elseif ('medium_large' == $masonry_image_mode) {
                  $portfolio_extra_class .= 'grve-image-square';
                  $image_size = 'medium_large';
                } elseif ('medium' == $masonry_image_mode) {
                  $portfolio_extra_class .= 'grve-image-square';
                  $image_size = 'medium';
                } elseif ('custom' == $masonry_image_mode) {
                  $masonry_size = get_post_meta($post_id, '_movedo_grve_portfolio_media_masonry_size', true);
                  $grve_masonry_data = movedo_ext_vce_get_custom_masonry_data($masonry_size);
                  $portfolio_extra_class .= $grve_masonry_data['class'];
                  $image_size = $grve_masonry_data['image_size'];
                } else {
                  $grve_masonry_data = movedo_ext_vce_get_masonry_data($portfolio_index, $columns);
                  $portfolio_extra_class .= $grve_masonry_data['class'];
                  $image_size = $grve_masonry_data['image_size'];
                }
              } else {
                $image_size = movedo_ext_vce_get_image_size($grid_image_mode);
              }
            } else {
              $image_size = movedo_ext_vce_get_image_size($carousel_image_mode);
              $portfolio_extra_class = 'mix grve-portfolio-item';
              echo '<div class="grve-carousel-item">';
            }

            // Hide Portfolio Like
            if ('hover-style-1' == $portfolio_style || 'hover-style-4' == $portfolio_style || 'hover-style-5' == $portfolio_style || 'hover-style-6' == $portfolio_style) {
              $hide_portfolio_like = 'yes';
            }

            //Portfolio Link
            $portfolio_link_exists = true;
            $grve_target = '_self';
            if (!empty($new_window)) {
              $grve_target = '_blank';
            }

            ob_start();

            if ('popup' == $portfolio_link_type) {
            ?><a class="grve-item-url" aria-label="<?php the_title_attribute(); ?>" href="<?php movedo_ext_vce_print_portfolio_image($image_popup_size_mode, 'link'); ?>"><?php
                                                                                                                                                                    } else if ('custom-link' == $portfolio_link_type) {
                                                                                                                                                                      if ('' == $link_mode) {
                                                                                                                                                                      ?><a class="grve-item-url" aria-label="<?php the_title_attribute(); ?>" href="<?php echo esc_url(get_permalink()); ?>"><?php
                                                                                                                                                                      } else if ('link' == $link_mode && !empty($link_url)) {
                                                                                                                                ?><a class="grve-item-url <?php echo esc_attr($link_class); ?>" aria-label="<?php the_title_attribute(); ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($grve_target); ?>"><?php
                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                  $portfolio_link_exists = false;
                                                                                                                                                                                                                }
                                                                                                                                                                                                              } else {
                                                                                                                                                                                                                  ?><a class="grve-item-url" aria-label="<?php the_title_attribute(); ?>" href="<?php echo esc_url(get_permalink()); ?>"><?php
                                                                                                                                                                                                              }

                                                                                                                                                                                                              $link_start = ob_get_clean();


                                                                                                                                                                                                              if ($portfolio_link_exists) {
                                                                                                                                                                                                                $link_end = '</a>';
                                                                                                                                                                                                              } else {
                                                                                                                                                                                                                $link_end = '';
                                                                                                                                                                                                              }

                                                                                                                                                                                                              $link_html = $link_start . $link_end;

                                                                                                                                                                                                              // Portfolio Content Classes
                                                                                                                                                                                                              $portfolio_content_classes = array('grve-content');
                                                                                                                                                                                                              if ('yes' == $show_title_or_caption) {
                                                                                                                                                                                                                if ('hover-style-7' == $portfolio_style) {
                                                                                                                                                                                                                  array_push($portfolio_content_classes, 'grve-align-left');
                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                  array_push($portfolio_content_classes, 'grve-align-left');
                                                                                                                                                                                                                }
                                                                                                                                                                                                                if ('hover-style-4' == $portfolio_style || 'hover-style-5' == $portfolio_style || 'hover-style-7' == $portfolio_style) {
                                                                                                                                                                                                                  array_push($portfolio_content_classes, 'grve-box-item grve-bg-' . $content_bg_color);
                                                                                                                                                                                                                }
                                                                                                                                                                                                              }
                                                                                                                                                                                                              $portfolio_content_class_string = implode(' ', $portfolio_content_classes);

                                                                                                                                                                                                              //Portfolio Title & Caption Color
                                                                                                                                                                                                              $text_color = 'white';
                                                                                                                                                                                                              $title_color = 'white';
                                                                                                                                                                                                              if ('hover-style-1' == $portfolio_style) {
                                                                                                                                                                                                                $text_color = 'inherit';
                                                                                                                                                                                                                $title_color = 'inherit';
                                                                                                                                                                                                              } elseif ('hover-style-2' == $portfolio_style || 'hover-style-3' == $portfolio_style) {
                                                                                                                                                                                                                if ('light' == $overlay_color) {
                                                                                                                                                                                                                  $text_color = 'content';
                                                                                                                                                                                                                  $title_color = 'black';
                                                                                                                                                                                                                }
                                                                                                                                                                                                              } elseif ('hover-style-4' == $portfolio_style || 'hover-style-5' == $portfolio_style || 'hover-style-7' == $portfolio_style) {
                                                                                                                                                                                                                $text_color = 'inherit';
                                                                                                                                                                                                                if ('white' == $content_bg_color) {
                                                                                                                                                                                                                  $title_color = 'black';
                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                  $title_color = 'white';
                                                                                                                                                                                                                }
                                                                                                                                                                                                              }

                                                                                                                                                                                                              //Portfolio Custom Overview
                                                                                                                                                                                                              if ('custom-overview' == $portfolio_overview_type) {
                                                                                                                                                                                                                $overview_mode = get_post_meta($post_id, '_movedo_grve_portfolio_overview_mode', true);
                                                                                                                                                                                                                $overview_text = get_post_meta($post_id, '_movedo_grve_portfolio_overview_text', true);
                                                                                                                                                                                                                $overview_text_heading = get_post_meta($post_id, '_movedo_grve_portfolio_overview_text_heading', true);
                                                                                                                                                                                                                $overview_bg_color = 'none';
                                                                                                                                                                                                                if ('color' == $overview_mode) {
                                                                                                                                                                                                                  $overview_color = get_post_meta($post_id, '_movedo_grve_portfolio_overview_color', true);
                                                                                                                                                                                                                  if (empty($overview_color)) {
                                                                                                                                                                                                                    $overview_color = 'black';
                                                                                                                                                                                                                  }
                                                                                                                                                                                                                  $overview_bg_color = get_post_meta($post_id, '_movedo_grve_portfolio_overview_bg_color', true);
                                                                                                                                                                                                                  if (empty($overview_bg_color)) {
                                                                                                                                                                                                                    $overview_bg_color = 'primary-1';
                                                                                                                                                                                                                  }
                                                                                                                                                                                                                  if (empty($overview_text_heading)) {
                                                                                                                                                                                                                    $overview_text_heading = 'h3';
                                                                                                                                                                                                                  }
                                                                                                                                                                                                                  $portfolio_extra_class .= ' grve-bg-overview';
                                                                                                                                                                                                                }
                                                                                                                                                                                                              } else {
                                                                                                                                                                                                                $overview_bg_color = 'none';
                                                                                                                                                                                                                $overview_mode = '';
                                                                                                                                                                                                              }



                                                                                                                                                                                                              $dag = get_field('dag', $post_id);
                                                                                                                                                                                                              if ($dag) {
                                                                                                                                                                                                                foreach ($dag as $d) {
                                                                                                                                                                                                                  $portfolio_extra_class .= ' dag_' . strtolower($d);
                                                                                                                                                                                                                }
                                                                                                                                                                                                              }

                                                                                                                                                                                                              $aktor = get_field('aktor', $post_id) ?: false;

                                                                                                                                                                                                              if ($aktor) {
                                                                                                                                                                                                                $portfolio_extra_class .= ' aktor_' . strtolower($aktor);
                                                                                                                                                                                                              }

                                                                                                                                                                                                              $portfolio_extra_class .= ' ' . strtolower(get_the_title($post_id));
                                                                                                                                                                                                              $image_atts = array();

                                                                                                                                                                                                              $cat_color = get_field('cat_color', $post_id);

                                                                                                                                                                                                              $categories = get_the_terms($post_id, 'arr-category');
                                                                                                                                                                                                              $catz = "";
                                                                                                                                                                                                              $colorz = "";
                                                                                                                                                                                                              if ($categories) {
                                                                                                                                                                                                                foreach ($categories as $cat) {
                                                                                                                                                                                                                  // Get the ACF field for each category
                                                                                                                                                                                                                  $cat_color = get_field('cat_color', 'term_' . $cat->term_id);
                                                                                                                                                                                                                  $catz .= $cat->slug;
                                                                                                                                                                                                                  $colorz .= $cat_color;
                                                                                                                                                                                                                }
                                                                                                                                                                                                              }


                                                                                                                                ?>
                    <article id="mixitup-<?php the_ID(); ?><?php echo uniqid('-'); ?>" <?php post_class($portfolio_extra_class); ?>>
                      <?php
                      if ('carousel' == $portfolio_mode) {
                      ?><div class="grve-carousel-item grve-hover-item grve-<?php echo esc_attr($portfolio_style); ?>"><?php
                                                                                                              } else {
                                                                                                                ?><div class="<?php echo esc_attr($isotope_inner_item_class_string); ?>"><?php
                                                                                                              }
                                                                                                              if ('color' != $overview_mode) {
                                                                                                                if ('hover-style-1' == $portfolio_style) {
                                                                                        ?>
                              <figure class="<?php echo esc_attr($image_effect_class_string); ?>">
                                <?php echo apply_filters('movedo_grve_vce_portfolio_link', $link_html, $post_id, $combined_atts); ?>
                                <div class="grve-bg-<?php echo esc_attr($overlay_color); ?> grve-hover-overlay grve-opacity-<?php echo esc_attr($overlay_opacity); ?>"></div>
                                <?php movedo_ext_vce_print_portfolio_image($image_size, '', $image_atts); ?>
                                <figcaption></figcaption>
                              </figure>
                              <?php if ('yes' == $show_title_or_caption) { ?>
                                <div class="<?php echo esc_attr($portfolio_content_class_string); ?>">
                                  <?php do_action('movedo_grve_vce_portfolio_content_first'); ?>
                                  <?php if ('yes' == $show_title) { ?>
                                    <<?php echo tag_escape($portfolio_title_heading_tag); ?> class="grve-title grve-text-<?php echo esc_attr($title_color); ?> grve-<?php echo esc_attr($portfolio_title_heading); ?>"><?php the_title(); ?></<?php echo tag_escape($portfolio_title_heading_tag); ?>>
                                  <?php } ?>
                                  <?php if ('yes' == $show_caption) { ?>
                                    <div class="grve-description grve-text-content"><?php echo wp_kses_post($caption); ?></div>
                                  <?php } ?>
                                  <?php do_action('movedo_grve_vce_portfolio_content_last'); ?>
                                </div>
                              <?php } ?>
                            <?php
                                                                                                                } else {
                            ?>


                              <figure class="<?php echo esc_attr($image_effect_class_string); ?>">





                                <?php echo apply_filters('movedo_grve_vce_portfolio_link', $link_html, $post_id, $combined_atts); ?>
                                <?php if (get_field('status')): ?>
                                  <div class="ribbon">Utsolgt!</div>
                                <?php endif; ?>
                                <?php if ('hover-style-6' != $portfolio_style) { ?>
                                  <div class="grve-bg-<?php echo esc_attr($overlay_color); ?> grve-hover-overlay grve-opacity-<?php echo esc_attr($overlay_opacity); ?>"></div>
                                <?php } else { ?>

                                  <div class="grve-gradient-overlay"></div>

                                <?php } ?>



                                <?php movedo_ext_vce_print_portfolio_image($image_size, '', $image_atts); ?>

                                <?php if ('yes' == $show_title_or_caption) { ?>
                                  <figcaption class="<?php echo esc_attr($portfolio_content_class_string); ?>">
                                    <?php $dated = get_field('dato', $post_id) ?: get_field('dato_periode', $post_id); ?>

                                    <?php if ($dated) { ?>
                                      <span <?php echo $catz == 'norsk-siderfestival' ? 'style="color: #333; background-color:' . $colorz . '"' : ''; ?> class="grve-date grve-text-white grve-h4"><?php echo $dated; ?><?php //echo get_the_date( 'd.F Y', $post_id );
                                                                                                                                                                                                                        ?></span>

                                    <?php } ?>
                                    <?php do_action('movedo_grve_vce_portfolio_content_first'); ?>
                                    <?php if ('yes' == $show_title) { ?>
                                      <<?php echo tag_escape($portfolio_title_heading_tag); ?> class="grve-title grve-text-<?php echo esc_attr($title_color); ?> grve-<?php echo esc_attr($portfolio_title_heading); ?>"><?php the_title(); ?></<?php echo tag_escape($portfolio_title_heading_tag); ?>>

                                    <?php } ?>


                                    <?php if ('hover-style-2' == $portfolio_style && 'yes' == $show_title && 'yes' == $show_caption) { ?>
                                      <div class="grve-line grve-text-<?php echo esc_attr($text_color); ?>"><span></span></div>

                                    <?php } ?>
                                    <?php if ('yes' == $show_caption) { ?>
                                      <div class="grve-description grve-text-<?php echo esc_attr($text_color); ?>"><?php echo wp_kses_post($caption); ?></div>
                                    <?php } ?>
                                    <?php
                                                                                                                    if (function_exists('movedo_grve_print_portfolio_like_counter') && 'yes' != $hide_portfolio_like) {
                                                                                                                      movedo_grve_print_portfolio_like_counter($text_color);
                                                                                                                    }
                                    ?>
                                    <?php do_action('movedo_grve_vce_portfolio_content_last'); ?>

                                  </figcaption>

                                <?php } else { ?>
                                  <figcaption></figcaption>
                                <?php } ?>
                              </figure>
                            <?php
                                                                                                                }
                                                                                                              } else {
                            ?>

                            <figure class="grve-image-hover grve-media grve-bg-<?php echo esc_attr($overview_bg_color); ?>">
                              <?php echo apply_filters('movedo_grve_vce_portfolio_link', $link_html, $post_id, $combined_atts); ?>
                              <?php movedo_ext_vce_print_portfolio_image($image_size, 'color', $image_atts); ?>
                              <?php if ('yes' == $show_title_or_caption) { ?>
                                <figcaption class="grve-content grve-align-center grve-custom-overview">
                                  <?php do_action('movedo_grve_vce_portfolio_content_first'); ?>
                                  <?php if ('yes' == $show_title) { ?>
                                    <<?php echo tag_escape($portfolio_title_heading_tag); ?> class="grve-title grve-text-<?php echo esc_attr($overview_color); ?> grve-<?php echo esc_attr($overview_text_heading); ?>"><?php the_title(); ?></<?php echo tag_escape($portfolio_title_heading_tag); ?>>
                                  <?php } ?>
                                  <?php if (!empty($overview_text)) { ?>
                                    <div class="grve-description grve-text-<?php echo esc_attr($overview_color); ?>"><?php echo wp_kses_post($overview_text); ?></div>
                                  <?php } ?>
                                  <?php do_action('movedo_grve_vce_portfolio_content_last'); ?>
                                </figcaption>
                              <?php } else { ?>
                                <figcaption></figcaption>
                              <?php } ?>
                            </figure>
                          <?php
                                                                                                              }
                          ?>
                          </div>
                    </article>
                  <?php
                  if ('carousel' == $portfolio_mode) {
                    echo '</div>';
                  }

                endwhile;
                if ('carousel' == $portfolio_mode) {
                  echo '</div>';
                }
                  ?>
          </div>
          <?php
          if ('yes' != $disable_pagination) {
            $total = $query->max_num_pages;
            $big = 999999999; // need an unlikely integer
            if ($total > 1) {
              echo '<div class="grve-pagination grve-link-text grve-heading-color">';

              if (get_option('permalink_structure')) {
                $format = 'page/%#%/';
              } else {
                $format = '&paged=%#%';
              }
              echo paginate_links(array(
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => $format,
                'current'    => max(1, $paged),
                'total'      => $total,
                'mid_size'    => 2,
                'type'      => 'list',
                'prev_text'  => '<i class="grve-icon-nav-left-small"></i>',
                'next_text'  => '<i class="grve-icon-nav-right-small"></i>',
                'add_args' => false,
              ));
              echo '</div>';
            }
          }
          ?>
      </div>
      </div>

      <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/mixitup.min.js"></script>
      <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/mixitup-multifilter.min-min.js"></script>
      <script>
        //var containerEl = document.querySelector('.mixitup-container');

        //var mixer = mixitup(containerEl);


        /*var r = document.querySelector(".mixitup-container"),
            o;
        r && (o = mixitup(r, {
            selectors: {
                target: ".mix"
            },
            multifilter: {
                enable: !0
            },
            callbacks: {
                onMixStart: function e(t, i) {}
            },
            animation: {
                duration: 250,
                nudge: !0,
                reverseOut: !1,
                effects: "fade translateZ(-100px)"
            }
        })),jQuery(".dropdown-reset").click((function() {
            jQuery(".select-selected.s-0").empty(), jQuery(".select-selected.s-0").html("Lokasjon"), jQuery(".select-selected.s-1").empty(), jQuery(".select-selected.s-1").html("Aktør")
        })), jQuery(".reset-all").click((function() {
            jQuery(".arrangement-reset").click(), jQuery(".day-reset").click(), jQuery(".fitfor-reset").click(), jQuery(".dropdown-reset").click()
        }))*/
        function closeAllSelect(e) {
          var t, i, s, n, r, o = [];
          for (t = document.getElementsByClassName("select-items"), i = document.getElementsByClassName("select-selected"), n = t.length, r = i.length, s = 0; s < r; s++) e == i[s] ? o.push(s) : i[s].classList.remove("select-arrow-active");
          for (s = 0; s < n; s++) o.indexOf(s) && t[s].classList.add("select-hide")
        }
        for (jQuery((function($) {
            var r = document.querySelector(".mixitup-container"),
              inputSearch = document.querySelector('[data-ref="input-search"]'),
              keyupTimeout,
              o;
            r && (o = mixitup(r, {
                selectors: {
                  target: ".mix"
                },
                multifilter: {
                  enable: !0
                },
                callbacks: {
                  onMixStart: function e(t, i) {},
                  onMixClick: function() {
                    // Reset the search if a filter is clicked

                    if (this.matches('[data-filter]')) {
                      inputSearch.value = '';
                    }
                  }
                },
                animation: {
                  duration: 250,
                  nudge: !0,
                  reverseOut: !1,
                  effects: "fade translateZ(-100px)"
                }
              })), inputSearch.addEventListener('keyup', function() {
                var searchValue;

                if (inputSearch.value.length < 3) {
                  // If the input value is less than 3 characters, don't send

                  searchValue = '';
                } else {
                  searchValue = inputSearch.value.toLowerCase().trim();
                }

                // Very basic throttling to prevent mixer thrashing. Only search
                // once 350ms has passed since the last keyup event

                clearTimeout(keyupTimeout);

                keyupTimeout = setTimeout(function() {
                  console.log(searchValue);
                  if (searchValue) {
                    // Use an attribute wildcard selector to check for matches

                    o.filter('[class*="' + searchValue + '"]');
                  } else {
                    // If no searchValue, treat as filter('all')

                    o.filter('all');
                  }
                }, 350);
              }),

              function filterByString(searchValue) {
                if (searchValue) {
                  console.log(searchValue);
                  // Use an attribute wildcard selector to check for matches

                  mixer.filter('[class*="' + searchValue + '"]');
                } else {
                  // If no searchValue, treat as filter('all')

                  mixer.filter('all');
                }
              },

              $(".dropdown-reset").click((function() {
                $(".select-selected.s-0").empty(), $(".select-selected.s-0").html("Lokasjon"), $(".select-selected.s-1").empty(), $(".select-selected.s-1").html("Aktør")
              })), $(".reset-all").click((function() {
                $(".arrangement-reset").click(), $(".day-reset").click(), $(".fitfor-reset").click(), $(".dropdown-reset").click()
              })), $(".related-events ul").hide(), $(".zoom-buttons .zoom-plus").on("click", (function() {
                $('#map .gm-bundled-control button[title="Zoom in"]').click()
              })), $(".zoom-buttons .zoom-minus").on("click", (function() {
                $('#map .gm-bundled-control button[title="Zoom out"]').click()
              })), $(".autocomplete span").click((function() {
                $("#myInput").toggleClass("toggle-search"), $(".autocomplete").toggleClass("active")
              })), $(".related-events p").click((function() {
                $(".related-events ul").slideToggle("fast"), $(".related-events p").toggleClass("active")
              }))
          })), l = (x = document.getElementsByClassName("custom-select")).length, i = 0; i < l; i++) {
          for (ll = (selElmnt = x[i].getElementsByTagName("select")[0]).length, (a = document.createElement("DIV")).setAttribute("class", "select-selected s-".concat(i)), a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML, x[i].appendChild(a), (b = document.createElement("DIV")).setAttribute("class", "select-items select-hide"), j = 1; j < ll; j++) {
            (c = document.createElement("BUTTON")).innerHTML = selElmnt.options[j].innerHTML;
            var lower = selElmnt.options[j].getAttribute("value");
            c.setAttribute("data-filter", "".concat(lower)), c.setAttribute("type", "button"), c.addEventListener("click", (function(e) {
              var t, i, s, n, r, o, a;
              for (o = (n = this.parentNode.parentNode.getElementsByTagName("select")[0]).length, r = this.parentNode.previousSibling, i = 0; i < o; i++)
                if (n.options[i].innerHTML == this.innerHTML) {
                  for (n.selectedIndex = i, r.innerHTML = this.innerHTML, a = (t = this.parentNode.getElementsByClassName("same-as-selected")).length, s = 0; s < a; s++) t[s].removeAttribute("class");
                  this.setAttribute("class", "same-as-selected");
                  break
                } r.click()
            })), b.appendChild(c)
          }
          x[i].appendChild(b), a.addEventListener("click", (function(e) {
            e.stopPropagation(), closeAllSelect(this), this.nextSibling.classList.toggle("select-hide"), this.classList.toggle("select-arrow-active")
          }))
        }
        document.addEventListener("click", closeAllSelect);
      </script>
  <?php

    else :
      do_action('movedo_grve_vce_portfolio_no_results');
    endif;
    wp_reset_postdata();

    return ob_get_clean();
  }
  add_shortcode('movedo_mixitup', 'movedo_ext_vce_mixitup_shortcode');
}

/**
 * Add shortcode to Visual Composer
 */

if (!function_exists('movedo_ext_vce_mixitup_shortcode_params')) {
  function movedo_ext_vce_mixitup_shortcode_params($tag)
  {
    return array(
      "name" => esc_html__("Mixitup", "movedo-extension"),
      "description" => esc_html__("Display Mixitup element in multiple styles", "movedo-extension"),
      "base" => $tag,
      "class" => "",
      "icon"      => "icon-wpb-grve-portfolio",
      "category" => esc_html__("Content", "js_composer"),
      "params" => array(
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Mixitup Mode", "movedo-extension"),
          "param_name" => "portfolio_mode",
          "admin_label" => true,
          'value' => array(
            esc_html__('Grid', 'movedo-extension') => 'grid',
            esc_html__('Masonry', 'movedo-extension') => 'masonry',
            esc_html__('Carousel', 'movedo-extension') => 'carousel',
          ),
          "description" => esc_html__("Select your portfolio mode", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Carousel Layout", "movedo-extension"),
          "param_name" => "carousel_layout",
          "value" => array(
            esc_html__("Classic", "movedo-extension") => 'layout-1',
            esc_html__("With title and description", "movedo-extension") => 'layout-2',
          ),
          "description" => 'Select your layout for Carousel Element',
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Title", "movedo-extension"),
          "param_name" => "title",
          "value" => "Sample Title",
          "description" => esc_html__("Enter your title here.", "movedo-extension"),
          "save_always" => true,
          "dependency" => array('element' => "carousel_layout", 'value' => array('layout-2')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Title Tag", "movedo-extension"),
          "param_name" => "heading_tag",
          "value" => array(
            esc_html__("h1", "movedo-extension") => 'h1',
            esc_html__("h2", "movedo-extension") => 'h2',
            esc_html__("h3", "movedo-extension") => 'h3',
            esc_html__("h4", "movedo-extension") => 'h4',
            esc_html__("h5", "movedo-extension") => 'h5',
            esc_html__("h6", "movedo-extension") => 'h6',
            esc_html__("div", "movedo-extension") => 'div',
          ),
          "description" => esc_html__("Title Tag for SEO", "movedo-extension"),
          "std" => 'h3',
          "dependency" => array('element' => "carousel_layout", 'value' => array('layout-2')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Title Size/Typography", "movedo-extension"),
          "param_name" => "heading",
          "value" => array(
            esc_html__("h1", "movedo-extension") => 'h1',
            esc_html__("h2", "movedo-extension") => 'h2',
            esc_html__("h3", "movedo-extension") => 'h3',
            esc_html__("h4", "movedo-extension") => 'h4',
            esc_html__("h5", "movedo-extension") => 'h5',
            esc_html__("h6", "movedo-extension") => 'h6',
            esc_html__("Leader Text", "movedo-extension") => 'leader-text',
            esc_html__("Subtitle Text", "movedo-extension") => 'subtitle-text',
            esc_html__("Small Text", "movedo-extension") => 'small-text',
            esc_html__("Link Text", "movedo-extension") => 'link-text',
          ),
          "description" => esc_html__("Title size and typography, defined in Theme Options - Typography Options", "movedo-extension"),
          "std" => 'h3',
          "dependency" => array('element' => "carousel_layout", 'value' => array('layout-2')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Carousel Title Custom Font Family", "movedo-extension"),
          "param_name" => "custom_font_family",
          "value" => array(
            esc_html__("Same as Typography", "movedo-extension") => '',
            esc_html__("Custom Font Family 1", "movedo-extension") => 'custom-font-1',
            esc_html__("Custom Font Family 2", "movedo-extension") => 'custom-font-2',
            esc_html__("Custom Font Family 3", "movedo-extension") => 'custom-font-3',
            esc_html__("Custom Font Family 4", "movedo-extension") => 'custom-font-4',

          ),
          "description" => esc_html__("Select a different font family, defined in Theme Options - Typography Options - Extras - Custom Font Family", "movedo-extension"),
          "std" => '',
          "dependency" => array('element' => "carousel_layout", 'value' => array('layout-2')),
        ),
        array(
          "type" => "textarea",
          "heading" => esc_html__("Text", "movedo-extension"),
          "param_name" => "content",
          "value" => "",
          "description" => esc_html__("Type your text.", "movedo-extension"),
          "dependency" => array('element' => "carousel_layout", 'value' => array('layout-2')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Text Style", "movedo-extension"),
          "param_name" => "text_style",
          "value" => array(
            esc_html__("None", "movedo-extension") => '',
            esc_html__("Leader", "movedo-extension") => 'leader-text',
            esc_html__("Subtitle", "movedo-extension") => 'subtitle',
          ),
          "description" => 'Select your text style',
          "dependency" => array('element' => "carousel_layout", 'value' => array('layout-2')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Alignment", "movedo-extension"),
          "param_name" => "align",
          "value" => array(
            esc_html__("Left", "movedo-extension") => 'left',
            esc_html__("Right", "movedo-extension") => 'right',
            esc_html__("Center", "movedo-extension") => 'center',
          ),
          "description" => '',
          "dependency" => array('element' => "carousel_layout", 'value' => array('layout-2')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Grid Image Size", "movedo-extension"),
          "param_name" => "grid_image_mode",
          'value' => array(
            esc_html__('Square Small Crop', 'movedo-extension') => 'square',
            esc_html__('Landscape Small Crop', 'movedo-extension') => 'landscape',
            esc_html__('Landscape Medium Crop', 'movedo-extension') => 'landscape-medium',
            esc_html__('Portrait Small Crop', 'movedo-extension') => 'portrait',
            esc_html__('Portrait Medium Crop', 'movedo-extension') => 'portrait-medium',
            esc_html__('Resize ( Large )', 'movedo-extension') => 'large',
            esc_html__('Resize ( Medium Large )', 'movedo-extension') => 'medium_large',
            esc_html__('Resize ( Medium )', 'movedo-extension') => 'medium',
          ),
          'std' => 'landscape',
          "description" => esc_html__("Select your Grid Image Size.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('grid')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Masonry Image Size", "movedo-extension"),
          "param_name" => "masonry_image_mode",
          'value' => array(
            esc_html__('Auto Crop', 'movedo-extension') => '',
            esc_html__('Resize ( Large )', 'movedo-extension') => 'large',
            esc_html__('Resize ( Medium Large )', 'movedo-extension') => 'medium_large',
            esc_html__('Resize ( Medium )', 'movedo-extension') => 'medium',
            esc_html__('Custom', 'movedo-extension') => 'custom',
          ),
          "description" => esc_html__("Select your Masonry Image Size.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('masonry')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Carousel Image Size", "movedo-extension"),
          "param_name" => "carousel_image_mode",
          'value' => array(
            esc_html__('Square Small Crop', 'movedo-extension') => 'square',
            esc_html__('Landscape Small Crop', 'movedo-extension') => 'landscape',
            esc_html__('Portrait Small Crop', 'movedo-extension') => 'portrait',
            esc_html__('Resize ( Large )', 'movedo-extension') => 'large',
            esc_html__('Resize ( Medium Large )', 'movedo-extension') => 'medium_large',
            esc_html__('Resize ( Medium )', 'movedo-extension') => 'medium',
          ),
          'std' => 'landscape',
          "description" => esc_html__("Select your Carousel Image Size.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        /*array(
					"type" => "dropdown",
					"heading" => esc_html__( "Large Screen Columns", "movedo-extension" ),
					"param_name" => "columns_large_screen",
					"value" => array(
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
					),
					"std" => '3',
					"description" => esc_html__( "Select your Portfolio Columns.", "movedo-extension" ),
					"dependency" => array( 'element' => "portfolio_mode", 'value' => array( 'grid', 'masonry' ) ),
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__( "Columns", "movedo-extension" ),
					"param_name" => "columns",
					"value" => array(
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
					),
					"std" => '3',
					"description" => esc_html__( "Select your Portfolio Columns.", "movedo-extension" ),
					"dependency" => array( 'element' => "portfolio_mode", 'value' => array( 'grid', 'masonry' ) ),
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__( "Tablet Landscape Columns", "movedo-extension" ),
					"param_name" => "columns_tablet_landscape",
					"value" => array(
						'2' => '2',
						'3' => '3',
						'4' => '4',
					),
					"std" => '2',
					"description" => esc_html__( "Select responsive column on tablet devices, landscape orientation.", "movedo-extension" ),
					"dependency" => array( 'element' => "portfolio_mode", 'value' => array( 'grid', 'masonry' ) ),
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__( "Tablet Portrait Columns", "movedo-extension" ),
					"param_name" => "columns_tablet_portrait",
					"value" => array(
						'2' => '2',
						'3' => '3',
						'4' => '4',
					),
					"std" => '2',
					"description" => esc_html__( "Select responsive column on tablet devices, portrait orientation.", "movedo-extension" ),
					"dependency" => array( 'element' => "portfolio_mode", 'value' => array( 'grid', 'masonry' ) ),
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__( "Mobile Columns", "movedo-extension" ),
					"param_name" => "columns_mobile",
					"value" => array(
						'1' => '1',
						'2' => '2',
					),
					"std" => '1',
					"description" => esc_html__( "Select responsive column on mobile devices.", "movedo-extension" ),
					"dependency" => array( 'element' => "portfolio_mode", 'value' => array( 'grid', 'masonry' ) ),
				),*/
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Items per page", "movedo-extension"),
          "param_name" => "items_per_page",
          "value" => array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
          ),
          "std" => '4',
          "description" => esc_html__("Number of images per page", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Items Tablet Landscape", "movedo-extension"),
          "param_name" => "items_tablet_landscape",
          "value" => array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
          ),
          "std" => '3',
          "description" => esc_html__("Select number of items on tablet devices, landscape orientation.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Items Tablet Portrait", "movedo-extension"),
          "param_name" => "items_tablet_portrait",
          "value" => array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
          ),
          "std" => '3',
          "description" => esc_html__("Select number of items on tablet devices, portrait orientation.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Items Mobile", "movedo-extension"),
          "param_name" => "items_mobile",
          "value" => array(
            '1' => '1',
            '2' => '2',
          ),
          "std" => '1',
          "description" => esc_html__("Select number of items on mobile devices.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Gutter between images", "movedo-extension"),
          "param_name" => "item_gutter",
          "value" => array(
            esc_html__("Yes", "movedo-extension") => 'yes',
            esc_html__("No", "movedo-extension") => 'no',
          ),
          "description" => esc_html__("Add gutter among images.", "movedo-extension"),
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Gutter Size", "movedo-extension"),
          "param_name" => "gutter_size",
          "value" => '30',
          "dependency" => array('element' => "item_gutter", 'value' => array('yes')),
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Items to show", "movedo-extension"),
          "param_name" => "items_to_show",
          "value" => '12',
          "description" => esc_html__("Maximum Portfolio Items to Show", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Loop", "movedo-extension"),
          "param_name" => "loop",
          "value" => array(
            esc_html__("Yes", "movedo-extension") => 'yes',
            esc_html__("No", "movedo-extension") => 'no',
          ),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Autoplay", "movedo-extension"),
          "param_name" => "auto_play",
          "value" => array(
            esc_html__("Yes", "movedo-extension") => 'yes',
            esc_html__("No", "movedo-extension") => 'no',
          ),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Slideshow Speed", "movedo-extension"),
          "param_name" => "slideshow_speed",
          "value" => '3000',
          "description" => esc_html__("Slideshow Speed in ms.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => 'dropdown',
          "heading" => esc_html__("Pause on Hover", "movedo-extension"),
          "param_name" => "pause_hover",
          "value" => array(
            esc_html__("Yes", "movedo-extension") => 'yes',
            esc_html__("No", "movedo-extension") => 'no',
          ),
          "std" => "no",
          "description" => esc_html__("If selected, carousel will be paused on hover", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Navigation Type", "movedo-extension"),
          "param_name" => "navigation_type",
          'value' => array(
            esc_html__('Style 1', 'movedo-extension') => '1',
            esc_html__('No Navigation', 'movedo-extension') => '0',
          ),
          "description" => esc_html__("Select your Navigation type.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Navigation Color", "movedo-extension"),
          "param_name" => "navigation_color",
          'value' => array(
            esc_html__('Dark', 'movedo-extension') => 'dark',
            esc_html__('Light', 'movedo-extension') => 'light',
          ),
          "description" => esc_html__("Select the background Navigation color.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Carousel Pagination", "movedo-extension"),
          "param_name" => "carousel_pagination",
          "value" => array(
            esc_html__("No", "movedo-extension") => 'no',
            esc_html__("Yes", "movedo-extension") => 'yes',
          ),
          "std" => "no",
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Carousel Pagination Speed", "movedo-extension"),
          "param_name" => "carousel_pagination_speed",
          "value" => '400',
          "description" => esc_html__("Pagination Speed in ms.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('carousel')),
        ),
        movedo_ext_vce_add_order_by(),
        movedo_ext_vce_add_order(),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("CSS Animation", "movedo-extension"),
          "param_name" => "animation",
          "value" => array(
            esc_html__("No", "movedo-extension") => '',
            esc_html__("Fade In", "movedo-extension") => "grve-fade-in",
            esc_html__("Fade In Up", "movedo-extension") => "grve-fade-in-up",
            esc_html__("Fade In Down", "movedo-extension") => "grve-fade-in-down",
            esc_html__("Fade In Left", "movedo-extension") => "grve-fade-in-left",
            esc_html__("Fade In Right", "movedo-extension") => "grve-fade-in-right",
            esc_html__("Zoom In", "movedo-extension") => "grve-zoom-in",
          ),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('grid', 'masonry')),
          "description" => esc_html__("Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "movedo-extension"),
          "std" => "grve-zoom-in",
        ),
        movedo_ext_vce_add_margin_bottom(),
        movedo_ext_vce_add_el_class(),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Image Title & Description Visibility", "movedo-extension"),
          "param_name" => "portfolio_title_caption",
          'value' => array(
            esc_html__('None', 'movedo-extension') => 'none',
            esc_html__('Title and Description', 'movedo-extension') => 'title-caption',
            esc_html__('Title Only', 'movedo-extension') => 'title-only',
            esc_html__('Description Only', 'movedo-extension') => 'caption-only',
          ),
          "std" => 'title-caption',
          "description" => esc_html__("Define the visibility for your portfolio title - description.", "movedo-extension"),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Portfolio Title Tag", "movedo-extension"),
          "param_name" => "portfolio_title_heading_tag",
          "value" => array(
            esc_html__("h1", "movedo-extension") => 'h1',
            esc_html__("h2", "movedo-extension") => 'h2',
            esc_html__("h3", "movedo-extension") => 'h3',
            esc_html__("h4", "movedo-extension") => 'h4',
            esc_html__("h5", "movedo-extension") => 'h5',
            esc_html__("h6", "movedo-extension") => 'h6',
            esc_html__("div", "movedo-extension") => 'div',
          ),
          "description" => esc_html__("Portfolio Title Tag for SEO", "movedo-extension"),
          "std" => 'h3',
          "dependency" => array('element' => "portfolio_title_caption", 'value' => array('title-caption', 'title-only')),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Portfolio Title Size/Typography", "movedo-extension"),
          "param_name" => "portfolio_title_heading",
          "value" => array(
            esc_html__("h1", "movedo-extension") => 'h1',
            esc_html__("h2", "movedo-extension") => 'h2',
            esc_html__("h3", "movedo-extension") => 'h3',
            esc_html__("h4", "movedo-extension") => 'h4',
            esc_html__("h5", "movedo-extension") => 'h5',
            esc_html__("h6", "movedo-extension") => 'h6',
            esc_html__("Leader Text", "movedo-extension") => 'leader-text',
            esc_html__("Subtitle Text", "movedo-extension") => 'subtitle-text',
            esc_html__("Small Text", "movedo-extension") => 'small-text',
            esc_html__("Link Text", "movedo-extension") => 'link-text',
          ),
          "description" => esc_html__("Portfolio Title size and typography, defined in Theme Options - Typography Options", "movedo-extension"),
          "std" => 'h3',
          "dependency" => array('element' => "portfolio_title_caption", 'value' => array('title-caption', 'title-only')),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Portfolio Style - Hovers", "movedo-extension"),
          "param_name" => "portfolio_style",
          'value' => array(
            esc_html__('Content Below Image', 'movedo-extension') => 'hover-style-1',
            esc_html__('Top Down Animated Content', 'movedo-extension') => 'hover-style-2',
            esc_html__('Left Right Animated Content', 'movedo-extension') => 'hover-style-3',
            esc_html__('Static Box Content', 'movedo-extension') => 'hover-style-4',
            esc_html__('Animated Box Content', 'movedo-extension') => 'hover-style-5',
            esc_html__('Gradient Overlay', 'movedo-extension') => 'hover-style-6',
            esc_html__('Animated Right Corner Box Content', 'movedo-extension') => 'hover-style-7',
          ),
          "description" => esc_html__("Select the hover style for the portfolio overview.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_title_caption", 'value' => array('title-caption', 'title-only', 'caption-only')),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => 'checkbox',
          "heading" => esc_html__("Hide Portfolio Likes", "movedo-extension"),
          "param_name" => "hide_portfolio_like",
          "value" => array(esc_html__("If selected, portfolio likes will be hidden", "movedo-extension") => 'yes'),
          "dependency" => array('element' => "portfolio_style", 'value' => array('hover-style-2', 'hover-style-3', 'hover-style-7')),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Title/Description Auto Resize", "movedo-extension"),
          "param_name" => "heading_auto_size",
          "value" => array(
            esc_html__("No", "movedo-extension") => 'no',
            esc_html__("Yes", "movedo-extension") => 'yes',
          ),
          "description" => esc_html__("If selected title/description will be automatically resized according to media width", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('masonry')),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Content Background Color", "movedo-extension"),
          "param_name" => "content_bg_color",
          'value' => array(
            esc_html__('White', 'movedo-extension') => 'white',
            esc_html__('Black', 'movedo-extension') => 'black',
          ),
          "description" => esc_html__("Select the background color for portfolio item content.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_style", 'value' => array('hover-style-4', 'hover-style-5', 'hover-style-7')),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Image Zoom Effect", "movedo-extension"),
          "param_name" => "zoom_effect",
          "value" => array(
            esc_html__("Zoom In", "movedo-extension") => 'in',
            esc_html__("Zoom Out", "movedo-extension") => 'out',
            esc_html__("None", "movedo-extension") => 'none',
          ),
          "description" => esc_html__("Choose the image zoom effect.", "movedo-extension"),
          'std' => 'none',
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Grayscale Effect", "movedo-extension"),
          "param_name" => "grayscale_effect",
          "value" => array(
            esc_html__("None", "movedo-extension") => 'none',
            esc_html__("Grayscale Image", "movedo-extension") => 'grayscale-image',
            esc_html__("Colored on Hover", "movedo-extension") => 'grayscale-image-hover',
          ),
          "description" => esc_html__("Choose the grayscale effect.", "movedo-extension"),
          'std' => 'none',
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Overlay Color", "movedo-extension"),
          "param_name" => "overlay_color",
          "param_holder_class" => "grve-colored-dropdown",
          "value" => array(
            esc_html__("Light", "movedo-extension") => 'light',
            esc_html__("Dark", "movedo-extension") => 'dark',
            esc_html__("Primary 1", "movedo-extension") => 'primary-1',
            esc_html__("Primary 2", "movedo-extension") => 'primary-2',
            esc_html__("Primary 3", "movedo-extension") => 'primary-3',
            esc_html__("Primary 4", "movedo-extension") => 'primary-4',
            esc_html__("Primary 5", "movedo-extension") => 'primary-5',
            esc_html__("Primary 6", "movedo-extension") => 'primary-6',
            esc_html__("Green", "movedo-extension") => 'green',
            esc_html__("Orange", "movedo-extension") => 'orange',
            esc_html__("Red", "movedo-extension") => 'red',
            esc_html__("Blue", "movedo-extension") => 'blue',
            esc_html__("Aqua", "movedo-extension") => 'aqua',
            esc_html__("Purple", "movedo-extension") => 'purple',
          ),
          "description" => esc_html__("Choose the image color overlay.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_style", 'value_not_equal_to' => array('hover-style-6')),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Overlay Opacity", "movedo-extension"),
          "param_name" => "overlay_opacity",
          "value" => array(
            esc_html__("0%", 'movedo-extension') => '0',
            esc_html__("10%", 'movedo-extension') => '10',
            esc_html__("20%", 'movedo-extension') => '20',
            esc_html__("30%", 'movedo-extension') => '30',
            esc_html__("40%", 'movedo-extension') => '40',
            esc_html__("50%", 'movedo-extension') => '50',
            esc_html__("60%", 'movedo-extension') => '60',
            esc_html__("70%", 'movedo-extension') => '70',
            esc_html__("80%", 'movedo-extension') => '80',
            esc_html__("90%", 'movedo-extension') => '90',
            esc_html__("100%", 'movedo-extension') => '100',
          ),
          "std" => '90',
          "description" => esc_html__("Choose the opacity for the overlay.", "movedo-extension"),
          "dependency" => array('element' => "portfolio_style", 'value_not_equal_to' => array('hover-style-6')),
          "group" => esc_html__("Titles & Hovers", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Link Type", "movedo-extension"),
          "param_name" => "portfolio_link_type",
          'value' => array(
            esc_html__('Classic Portfolio', 'movedo-extension') => 'item',
            esc_html__('Gallery Usage', 'movedo-extension') => 'popup',
            esc_html__('Custom Link', 'movedo-extension') => 'custom-link',
          ),
          "description" => esc_html__("Select the link type of your portfolio items.", "movedo-extension"),
          "group" => esc_html__("Extras", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Image Popup Size", "movedo-extension"),
          "param_name" => "image_popup_size",
          'value' => array(
            esc_html__('Large', 'movedo-extension') => 'large',
            esc_html__('Extra Extra Large', 'movedo-extension') => 'extra-extra-large',
            esc_html__('Full', 'movedo-extension') => 'full',
          ),
          "std" => 'extra-extra-large',
          "dependency" => array('element' => "portfolio_link_type", 'value' => array('popup')),
          "description" => esc_html__("Select size for your popup image.", "movedo-extension"),
          "group" => esc_html__("Extras", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Overview Type", "movedo-extension"),
          "param_name" => "portfolio_overview_type",
          'value' => array(
            esc_html__('Default', 'movedo-extension') => '',
            esc_html__('Custom Overview', 'movedo-extension') => 'custom-overview',
          ),
          "dependency" => array('element' => "portfolio_style", 'value' => array('hover-style-2', 'hover-style-3', 'hover-style-4', 'hover-style-5', 'hover-style-6', 'hover-style-7')),
          "description" => esc_html__("Select the overview type of your portfolio items.", "movedo-extension"),
          "group" => esc_html__("Extras", "movedo-extension"),
        ),
        array(
          "type" => 'checkbox',
          "heading" => esc_html__("Enable Loader", "movedo-extension"),
          "param_name" => "item_spinner",
          "description" => esc_html__("If selected, this will enable a graphic spinner before load.", "movedo-extension"),
          "value" => array(esc_html__("Enable Loader.", "movedo-extension") => 'yes'),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('grid', 'masonry')),
          "group" => esc_html__("Extras", "movedo-extension"),
        ),
        array(
          "type" => 'checkbox',
          "heading" => esc_html__("Disable Pagination", "movedo-extension"),
          "param_name" => "disable_pagination",
          "description" => esc_html__("If selected, pagination will not be shown.", "movedo-extension"),
          "value" => array(esc_html__("Disable Pagination.", "movedo-extension") => 'yes'),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('grid', 'masonry')),
          "group" => esc_html__("Extras", "movedo-extension"),
        ),
        array(
          "type" => 'dropdown',
          "heading" => esc_html__("Filter", "movedo-extension"),
          "param_name" => "portfolio_filter",
          "value" => array(
            esc_html__("No", "movedo-extension") => '',
            esc_html__("Yes", "movedo-extension") => 'yes',
          ),
          "description" => esc_html__("If selected, an isotope filter will be displayed.", "movedo-extension") . " " . esc_html__("Enable Portfolio Filter ( Only for All or Multiple Categories )", "movedo-extension"),
          "dependency" => array('element' => "portfolio_mode", 'value' => array('grid', 'masonry')),
          "group" => esc_html__("Filters", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Filter Order By", "movedo-extension"),
          "param_name" => "filter_order_by",
          "value" => array(
            esc_html__("Default ( Unordered )", "movedo-extension") => '',
            esc_html__("ID", "movedo-extension") => 'id',
            esc_html__("Slug", "movedo-extension") => 'slug',
            esc_html__("Title", "movedo-extension") => 'title',
          ),
          "description" => '',
          "dependency" => array('element' => "portfolio_filter", 'value' => array('yes')),
          "group" => esc_html__("Filters", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Filter Order", "movedo-extension"),
          "param_name" => "filter_order",
          "value" => array(
            esc_html__("Ascending", "movedo-extension") => 'ASC',
            esc_html__("Descending", "movedo-extension") => 'DESC',
          ),
          "dependency" => array('element' => "portfolio_filter", 'value' => array('yes')),
          "description" => '',
          "group" => esc_html__("Filters", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Filter Style", "movedo-extension"),
          "param_name" => "filter_style",
          "value" => array(
            esc_html__("Simple", "movedo-extension") => 'simple',
            esc_html__("Button", "movedo-extension") => 'button',
            esc_html__("Classic", "movedo-extension") => 'classic',

          ),
          "dependency" => array('element' => "portfolio_filter", 'value' => array('yes')),
          "group" => esc_html__("Filters", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Filter Shape", "movedo-extension"),
          "param_name" => "filter_shape",
          "value" => array(
            esc_html__("Square", "movedo-extension") => 'square',
            esc_html__("Round", "movedo-extension") => 'round',
            esc_html__("Extra Round", "movedo-extension") => 'extra-round',
          ),
          "dependency" => array('element' => "filter_style", 'value' => array('button')),
          "group" => esc_html__("Filters", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Filter Color", "movedo-extension"),
          "param_name" => "filter_color",
          "param_holder_class" => "grve-colored-dropdown",
          "value" => array(
            esc_html__("Primary 1", "movedo-extension") => 'primary-1',
            esc_html__("Primary 2", "movedo-extension") => 'primary-2',
            esc_html__("Primary 3", "movedo-extension") => 'primary-3',
            esc_html__("Primary 4", "movedo-extension") => 'primary-4',
            esc_html__("Primary 5", "movedo-extension") => 'primary-5',
            esc_html__("Primary 6", "movedo-extension") => 'primary-6',
            esc_html__("Green", "movedo-extension") => 'green',
            esc_html__("Orange", "movedo-extension") => 'orange',
            esc_html__("Red", "movedo-extension") => 'red',
            esc_html__("Blue", "movedo-extension") => 'blue',
            esc_html__("Aqua", "movedo-extension") => 'aqua',
            esc_html__("Purple", "movedo-extension") => 'purple',
            esc_html__("Black", "movedo-extension") => 'black',
            esc_html__("Grey", "movedo-extension") => 'grey',
            esc_html__("White", "movedo-extension") => 'white',
          ),
          "dependency" => array('element' => "filter_style", 'value' => array('button')),
          "group" => esc_html__("Filters", "movedo-extension"),
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Filter Alignment", "movedo-extension"),
          "param_name" => "portfolio_filter_align",
          "value" => array(
            esc_html__("Left", "movedo-extension") => 'left',
            esc_html__("Right", "movedo-extension") => 'right',
            esc_html__("Center", "movedo-extension") => 'center',
          ),
          "description" => '',
          "dependency" => array('element' => "portfolio_filter", 'value' => array('yes')),
          "group" => esc_html__("Filters", "movedo-extension"),
        ),
        array(
          "type" => 'dropdown',
          "heading" => esc_html__("Filter Go To Top", "movedo-extension"),
          "param_name" => "filter_gototop",
          "value" => array(
            esc_html__("Yes", "movedo-extension") => 'yes',
            esc_html__("No", "movedo-extension") => 'no',

          ),
          "std" => "yes",
          "description" => esc_html__("Animate to the top of the filter after clicking", "movedo-extension"),
          "dependency" => array('element' => "portfolio_filter", 'value' => array('yes')),
          "group" => esc_html__("Filters", "movedo-extension"),
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Exclude Posts", "movedo-extension"),
          "param_name" => "exclude_posts",
          "value" => '',
          "description" => esc_html__("Type the post ids you want to exclude separated by comma ( , ).", "movedo-extension"),
          "group" => esc_html__("Categories", "movedo-extension"),
        ),
        array(
          "type" => "movedo_ext_multi_checkbox",
          "heading" => __("Portfolio Categories", "movedo-extension"),
          "param_name" => "categories",
          "value" => movedo_ext_vce_get_posttype_categories('arr-category'),
          "description" => esc_html__("Select all or multiple categories.", "movedo-extension"),
          "admin_label" => true,
          "group" => esc_html__("Categories", "movedo-extension"),
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__("Include Specific Posts", "movedo-extension"),
          "param_name" => "include_posts",
          "value" => '',
          "description" => esc_html__("Type the specific post ids you want to include separated by comma ( , ). Note: If you define specific post ids, Exclude Posts and Categories will have no effect.", "movedo-extension"),
          "group" => esc_html__("Categories", "movedo-extension"),
        ),
        // array(
        // "type" => "movedo_ext_taxonomy_tree",
        // "heading" => __("Portfolio Categories", "movedo-extension" ),
        // "param_name" => "categories",
        // "value" => '',
        // "taxonomy" => 'arr-category',
        // "description" => esc_html__( "Select all or multiple categories.", "movedo-extension" ),
        // "admin_label" => true,
        // "group" => esc_html__( "Categories", "movedo-extension" ),
        // ),
      ),
    );
  }
}

add_action('wp_footer', 'mixitup_custom_css');
function mixitup_custom_css()
{
  ?>
  <style>
    .custom-select {
      position: relative;
      width: 100%;
      height: 3rem
    }

    .custom-select select {
      display: none
    }

    .program .button-grid button:first-child {
      margin-left: 0;
    }

    .select-selected {
      background-color: white;
      box-shadow: .1875rem .1875rem .4375rem rgba(0, 0, 0, 0.1);
      text-align: left;
      /*height: 3rem;*/
      padding: 0;
      margin-left: 0 !important;
    }

    .select-selected:after {
      /*position: absolute;
    content: "";
    top: 14px;
    right: 10px;
    width: 0;
    height: 0;
    border: 6px solid transparent;
    border-color: #fff transparent transparent transparent*/
      content: " ";
      border: solid #fff;
      border-width: 0 3px 3px 0;
      display: inline-block;
      padding: 8px;
      transform: rotate(45deg);
      -webkit-transform: rotate(45deg);
      margin-left: 20px;

    }

    .select-selected.select-arrow-active:after {
      border-color: transparent transparent #fff transparent;
      top: 7px
    }

    .select-items div,
    .select-selected {
      color: #428B59;
      padding: 0;
      border: 0px solid transparent;
      border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
      cursor: pointer;
      line-height: 2.9375rem
    }

    .select-items {
      position: absolute;
      background-color: white;
      padding-top: .9375rem;
      padding-bottom: 1.25rem;
      box-shadow: .1875rem .1875rem .4375rem rgba(0, 0, 0, 0.1);
      top: 0;
      left: 0;
      right: 0;
      z-index: 99
    }

    .select-hide {
      display: none
    }

    .select-items div:hover,
    .same-as-selected {
      background-color: rgba(0, 0, 0, 0.1)
    }

    .program .grve-hover-item .grve-content .grve-title {
      line-height: normal;
      font-size: 22px;
      letter-spacing: normal;
      margin-top: 15px;
    }

    .program form {
      margin: 0 0 15px 0 !important;
    }

    .program .button-grid {
      display: flex;
      justify-content: space-around;
    }

    .program .button-grid button:first-child {
      margin-left: 0 !important;
    }

    .program .filter {
      padding-left: 20px;
      padding-right: 20px;
      margin-bottom: 40px;
    }

    .program .select-items button,
    .program .button-grid button,
    .program .select-selected,
    .program .search-wrapper input,
    .program .search-wrapper button {
      text-align: left !important;
      font-size: 18px !important;
      font-family: GT-Pressura-Bold, sans-serif, serif !important;
    }


    .program .button-grid button,
    .program .select-selected,
    .program .search-wrapper button {
      width: 100%;
      margin: 5px;
      padding: 0.6em 0.6em !important;

    }

    .program {
      position: relative;
      overflow: hidden
    }


    .program .filter .filter-container {
      row-gap: 0
    }

    .program .filter form {
      margin-left: 1.875rem;
      margin-right: 2.8125rem
    }

    .program .filter form fieldset {
      border: none;
      padding: 0
    }

    .program .filter form fieldset p {
      font-family: GT-Pressura-Bold, sans-serif, serif;
      font-size: .9375rem;
      line-height: 1.125rem;
      color: #428B59;
      padding-bottom: .9375rem
    }

    .mixitup-container .grve-date.grve-h4 {
      background: #ff8272;
      padding: 5px 10px;
      margin-bottom: 10px !important;
    }

    #grve-theme-wrapper .program .filter form fieldset button:not(.grve-custom-btn):not(.vc_general):not(.tribe-events-c-subscribe-dropdown__button-text):not(.tribe-events-calendar-month__day-cell--mobile):not(.tribe-events-c-top-bar__datepicker-button):not(.tribe-events-c-nav__next):not(.tribe-events-c-nav__prev) {
      background-color: white;
      color: #ff8272;
    }

    /*.program .filter form fieldset button {
    background-color: white;
    color: #428B59;
    font-family: SMedium,sans-serif,serif;
    font-size: .875rem;
    line-height: 1.0625rem;
    box-shadow: .1875rem .1875rem .4375rem rgba(0,0,0,0.1);
    padding: 0 1.5rem;
    height: 3rem;
    text-align: center;
    margin: 0 .9375rem 1.875rem 0;
    display: inline-block;
    border: none;
    outline: none;
    transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1)
}*/

    .program .filter form fieldset button.mixitup-control-active,
    .program .filter form fieldset .custom-select.mixitup-control-active .select-selected {
      background-color: #ff8272 !important;
      color: #ffffff !important;
      transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1);
      font-family: GT-Pressura-Bold, sans-serif, serif;

      line-height: 1.4;
      text-transform: uppercase;
    }

    .program .filter form fieldset button:hover {
      cursor: pointer
    }

    .program .filter fieldset .dropdown-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      column-gap: .9375rem;
      margin-bottom: 1.6875rem
    }

    .program .filter fieldset .dropdown-wrapper .custom-select button {
      box-shadow: none;
      display: block;
      height: 1.875rem;
      margin: 0 0 .375rem 0;
      font: 15px/20px SMedium, sans-serif, serif;
      width: 100%;
      text-align: left;
      padding: 0 .9375rem;
      transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1)
    }

    .program .filter form fieldset .dropdown-wrapper .custom-select button.dropdown-reset {
      box-shadow: none;
      background: #428B59;
      color: #fff;
      width: 3rem;
      height: 3rem;
      text-align: center;
      padding: 0
    }

    .program .filter form fieldset .dropdown-wrapper .custom-select button.dropdown-reset:hover {
      background: #428B59;
      color: #fff
    }

    .program .filter form fieldset .dropdown-wrapper .custom-select button.mixitup-control-active {
      background-color: white;
      color: #428B59
    }

    .program .filter form fieldset .dropdown-wrapper .custom-select button:hover {
      transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1);
      background-color: rgba(178, 229, 197, 0.5)
    }

    .program .filter form fieldset .show-results-wrapper {
      display: grid;
      grid-template-columns: 1fr 6.5625rem;
      column-gap: .9375rem
    }

    .program .filter form fieldset .show-results-wrapper a.show-results {
      background: #53BD77;
      color: white;
      width: 100%;
      height: 3rem;
      font: 14px/17px SMedium, sans-serif, serif;
      padding: 0;
      text-align: center;
      margin: 0 0 1.875rem 0;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      outline: none;
      transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1);
      box-shadow: .1875rem .1875rem .4375rem rgba(0, 0, 0, 0.1);
      text-decoration: none
    }

    .program .filter form fieldset .show-results-wrapper a.reset-all {
      background: #F44831;
      max-width: 6.9375rem;
      width: 100%;
      font: 14px/17px SMedium, sans-serif, serif;
      color: #fff;
      padding: 0;
      margin: 0;
      text-align: center;
      margin: 0 0 1.875rem 0;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      outline: none;
      transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1);
      box-shadow: .1875rem .1875rem .4375rem rgba(0, 0, 0, 0.1);
      text-decoration: none
    }

    .program .after-filter {
      padding: 3.75rem 0 0 0
    }


    #grve-theme-wrapper .program .filter .input-search {
      background: #fff;
      border: none;
      flex: 99;
      width: inherit !important;
      display: inherit;
      margin: 0;
    }

    #grve-theme-wrapper .program .search-wrapper button {
      width: inherit;
      margin: 0 0 0 5px !important;
      background: #ff8272 !important;
      color: #fff !important;
    }

    #grve-theme-wrapper .program .search-wrapper {
      display: flex;
    }

    .mix,
    .gap {
      display: inline-block;
      vertical-align: top;
    }


    @media screen and (max-width: 47.9375em) {
      .program .mix {
        width: inherit;
        display: inherit;
        padding-left: 0 !important;
        padding-right: 0 !important;

      }

      .program {
        position: relative;
        overflow: hidden
      }

      .program .filter {
        padding-left: 0px;
        padding-right: 0px;
        margin-bottom: 40px;
      }

      /*.program .filter {
                    padding: 1.875rem 0 1.875rem 0;
                    background-color: #EDE8F6
                }*/

      .program .filter .filter-container {
        row-gap: 0
      }

      .program .filter form {
        margin-left: 0;
        margin-right: 0
      }

      .program .filter form fieldset {
        border: none;
        padding: 0;
        margin: 0
      }

      .program .filter form fieldset p {
        font-family: SBold, sans-serif, serif;
        font-size: .9375rem;
        line-height: 1.125rem;
        color: #428B59;
        padding-bottom: .875rem
      }

      .program .filter form fieldset button {
        background-color: white;
        color: #428B59;
        font-family: SMedium, sans-serif, serif;
        font-size: .9375rem;
        line-height: 1.0625rem;
        box-shadow: .1875rem .1875rem .4375rem rgba(0, 0, 0, 0.1);
        padding: 0 1.5rem;
        /*height: 2.8125rem;*/
        text-align: center;
        margin: 0 .9375rem .9375rem 0;
        display: inline-block;
        border: none;
        outline: none;
        transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1)
      }

      .program .filter form fieldset button.mixitup-control-active {
        color: white;
        background-color: #428B59;
        transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1)
      }

      .program .filter form fieldset button:hover {
        cursor: pointer
      }

      .program .filter form fieldset .button-grid {
        margin-bottom: 1.875rem;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: .9375rem
      }

      .program .filter form fieldset .button-grid button {
        padding: 0;
        margin: 0
      }

      .program .filter form fieldset .dropdown-wrapper {
        display: grid;
        margin-bottom: 1.5rem
      }

      .program .filter form fieldset .dropdown-wrapper button {
        box-shadow: none;
        display: block;
        height: 1.875rem;
        margin: 0 0 .375rem 0;
        font: 15px / 20px SMedium, sans-serif, serif;
        width: 100%;
        text-align: left;
        padding: 0 .9375rem;
        transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1)
      }

      .program .filter form fieldset .dropdown-wrapper button.dropdown-reset {
        box-shadow: none;
        background: #428B59;
        color: #fff;
        width: 2.8125rem;
        height: 2.8125rem;
        text-align: center;
        padding: 0
      }

      .program .filter form fieldset .dropdown-wrapper button.dropdown-reset:hover {
        background: #428B59;
        color: #fff
      }

      .program .filter form fieldset .dropdown-wrapper button.mixitup-control-active {
        background-color: white;
        color: #428B59
      }

      .program .filter form fieldset .dropdown-wrapper button:hover {
        transition: all 0.3s cubic-bezier(0.77, 0.2, 0.05, 1);
        background-color: rgba(178, 229, 197, 0.5)
      }

      .program .filter form fieldset .show-results-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: .9375rem
      }

      .program .filter form fieldset .show-results-wrapper a.show-results {
        background: #53BD77;
        color: white;
        max-width: 39rem;
        width: 100%;
        height: 2.8125rem;
        font: 14px / 17px SMedium, sans-serif, serif;
        padding: 0;
        text-decoration: none
      }

      .program .filter form fieldset .show-results-wrapper a.reset-all {
        background: #F44831;
        max-width: 100%;
        height: 2.8125rem;
        width: 100%;
        font: 14px / 17px SMedium, sans-serif, serif;
        color: #fff;
        padding: 0;
        margin: 0;
        text-decoration: none
      }

      .program .after-filter {
        padding: 3.75rem 0 0 0
      }

      .program .after-filter .single-list {
        margin-left: .9375rem;
        padding-bottom: 2.8125rem
      }

      .program .after-filter .single-list h3 {
        color: #F44831;
        font: 20px / 26px SBold, sans-serif, serif;
        padding-bottom: .3125rem
      }

      .program .after-filter .single-list h2 {
        padding-bottom: .4375rem
      }

      .program .after-filter .single-list h2 a {
        color: #F44831;
        font: 20px / 26px SBold, sans-serif, serif;
        text-decoration: none
      }

      .program .after-filter .single-list .info {
        padding-bottom: 1.25rem
      }

      .program .after-filter .single-list .info p {
        color: #428B59;
        font: 18px / 22px SMedium, sans-serif, serif;
        padding-bottom: .625rem
      }

      .program .after-filter .single-list .desc p {
        color: #428B59;
        font: 16px / 30px SRegular, sans-serif, serif;
        padding-bottom: .625rem
      }

      .program .after-filter .single-list .desc p a {
        display: inline-block;
        margin-left: .3125rem;
        font: 16px / 26px SRegular, sans-serif, serif;
        color: #428B59;
        text-decoration: none;
        background: #FFE97A;
        height: 1.625rem;
        padding: 0 .4375rem .125rem;
        position: relative
      }

      .program .after-filter .single-list .desc p a:after {
        content: '';
        position: absolute;
        border-bottom: .0625rem solid #428B59;
        width: 100%;
        bottom: -.1875rem;
        left: 0
      }

      .custom-select {
        position: relative;
        font: 15px / 37px SMedium, sans-serif, serif;
        width: 100%;
        /*height: 2.8125rem !important*/
      }

      .custom-select select {
        display: none
      }

      .select-selected {
        background-color: white;
        box-shadow: .1875rem .1875rem .4375rem rgba(0, 0, 0, 0.1);
        text-align: center;
        /*height: 2.8125rem !important;*/
        padding: 0
      }


      .select-items div,
      .select-selected {
        color: #428B59;
        padding: 0;
        border: 0px solid transparent;
        border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
        cursor: pointer;
        line-height: 2.9375rem
      }

      .select-items {
        position: absolute;
        background-color: white;
        padding-top: .9375rem;
        padding-bottom: 1.25rem;
        box-shadow: .1875rem .1875rem .4375rem rgba(0, 0, 0, 0.1);
        top: 0;
        left: 0;
        right: 0;
        z-index: 99
      }

      .select-hide {
        display: none
      }

      .select-items div:hover,
      .same-as-selected {
        background-color: rgba(0, 0, 0, 0.1)
      }
    }



    .grve-hover-item .grve-content .grve-title {
      line-height: 2em !important;
      margin-bottom: 6px;
    }

    /*
.mix {
background: #fff;
border-top: .5rem solid currentColor;
border-radius: 2px;
margin-bottom: 1rem;
position: relative;
}

.mix:before {
content: '';
display: inline-block;
padding-top: 56.25%;
}*/

    /* Grid Breakpoints
---------------------------------------------------------------------- */

    /* 2 Columns */
    @media only screen and (min-width: 501px) {

      .mixitup-container .mix,
      .mixitup-container .gap {
        width: calc(100%/2 - (((2 - 1) * 1rem) / 2));
      }
    }
  </style>
<?php
}

if (function_exists('vc_lean_map')) {
  vc_lean_map('movedo_mixitup', 'movedo_ext_vce_mixitup_shortcode_params');
} else if (function_exists('vc_map')) {
  $attributes = movedo_ext_vce_mixitup_shortcode_params('movedo_mixitup');
  vc_map($attributes);
}
