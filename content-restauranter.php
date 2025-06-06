<style type="text/css">
  #grve-main-content .grve-main-content-wrapper,
  #grve-sidebar {
    padding-top: 0px;
    padding-bottom: 60px;
  }
</style>

<?php if (is_singular()) {
  $movedo_grve_disable_media = movedo_grve_post_meta('_movedo_grve_disable_media'); ?>

  <article id="post-<?php the_ID(); ?>" <?php post_class('grve-single-post'); ?> itemscope itemType="http://schema.org/BlogPosting">

    <div id="grve-single-content">

      <?php movedo_grve_print_post_structured_data(); ?>


      <div class="grve-section grve-row-section grve-fullwidth-background grve-padding-top-6x grve-padding-bottom-6x grve-bg-none">

        <div class="grve-container">
          <div class="grve-row grve-bookmark grve-columns-gap-0">
            <div class="grve-column wpb_column grve-column-1">
              <div class="grve-column-wrapper vc_custom_1657007730655" style="padding-top:8%; padding-bottom:8%;">
              </div>
            </div>
          </div>

        </div>

        <?php if (has_post_thumbnail()) : ?>
          <div class="grve-background-wrapper">
            <div class="grve-bg-image grve-bg-center-center grve-bg-image-id-1010089 show" style="background-image: url(<?= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>);"></div>
          </div>
        <?php endif; ?>

      </div>
      <div itemprop="articleBody">

        <div class="grve-section grve-row-section grve-fullwidth-background grve-padding-top-3x grve-padding-bottom-3x grve-bg-none">
          <div class="grve-container ">
            <div class="grve-row grve-bookmark grve-columns-gap-30 grve-rtl-columns-reverse">

              <div class="grve-column wpb_column grve-column-1-4 grve-bg-none">
                <div class="grve-column-wrapper">
                  <div class="grve-container">

                    <h3 class="grve-element grve-title grve-align-left grve-h2"><span>FAKTA</span></h3>

                    <div class="grve-element grve-text grve-leader-text">

                      <?php $sidebar_text = get_field('sidebar_text');
                      if ($sidebar_text) : ?>
                        <div class="wysiwyg-sidebar">
                          <?= $sidebar_text; ?>
                        </div>
                      <?php endif; ?>

                      <?php
                      $sidebar_link = get_field('sidebar_link');
                      if ($sidebar_link) : $sidebar_link_target = $sidebar_link["target"] ? $sidebar_link["target"] : "_self"; ?>
                        <div class="link-wrap">
                          <a class="grve-btn grve-btn-large grve-square grve-bg-primary-6 grve-bg-hover-primary-3" href="<?= esc_url($sidebar_link["url"]); ?>" target="<?= esc_attr($sidebar_link_target); ?>"><?= esc_html($sidebar_link["title"]); ?></a>
                        </div>
                      <?php endif; ?>

                    </div>
                    <br />
                    Del på Facebook
                    <br />
                    <?php echo do_shortcode('[movedo_social social_facebook="yes"]'); ?>
                  </div>
                </div>
              </div>



              <?php $main_wysiwyg = get_field('main_content_wysiwyg');
              if ($main_wysiwyg) : ?>
                <div class="grve-column wpb_column grve-column-3-4 grve-padding-bottom-2x">
                  <div class="grve-column-wrapper">
                    <div class="grve-element grve-text grve-leader-text">
                      <?= $main_wysiwyg; ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>


            </div>
          </div>


        </div>

        <?php the_content(); ?>

  </article>


<?php } else {

  $blog_mode = movedo_grve_option('blog_mode', 'large');

  $post_style = movedo_grve_post_meta('_movedo_grve_post_standard_style');
  $bg_mode = false;

  if (('masonry' == $blog_mode || 'grid' == $blog_mode) && 'movedo' == $post_style) {
    $bg_mode = true;
  }
  if ($bg_mode) {
    $movedo_grve_post_class = movedo_grve_get_post_class("grve-style-2");
    $bg_color = movedo_grve_post_meta('_movedo_grve_post_standard_bg_color', 'black');
    $bg_opacity = movedo_grve_post_meta('_movedo_grve_post_standard_bg_opacity', '70');
    $bg_options = array(
      'bg_color' => $bg_color,
      'bg_opacity' => $bg_opacity,
    );
  } else {
    $movedo_grve_post_class = movedo_grve_get_post_class();
  }

?>

  <!-- Article -->
  <article id="post-<?php the_ID(); ?>" <?php post_class($movedo_grve_post_class); ?> itemscope itemType="http://schema.org/BlogPosting">
    <?php do_action('movedo_grve_inner_post_loop_item_before'); ?>
    <?php if ($bg_mode) { ?>
      <?php movedo_grve_print_post_bg_image_container($bg_options); ?>
    <?php } else { ?>
      <?php movedo_grve_print_post_feature_media('image'); ?>
    <?php } ?>

    <div class="grve-post-content-wrapper">
      <div class="grve-post-content">
        <?php movedo_grve_print_post_meta_top(); ?>
        <?php movedo_grve_print_post_structured_data(); ?>
        <div itemprop="articleBody">
          <?php movedo_grve_print_post_excerpt(); ?>
        </div>
      </div>
    </div>
    <?php do_action('movedo_grve_inner_post_loop_item_after'); ?>
  </article>

  <!-- End Article -->

<?php

}

//Omit closing PHP tag to avoid accidental whitespace output errors.