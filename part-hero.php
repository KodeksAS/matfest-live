<?php
// Hero fields
$use_hero = get_field('use_hero');
$media_type = get_field('hero_media_type');

// Desktop image:
$imgDesktop = get_field('image_desktop');

$img1 = get_field('image_one');
$img2 = get_field('image_two');
$imgm1 = get_field('image_mobile_one');
$imgm2 = get_field('image_mobile_two');

// Logos:
$logo1 = get_field('logo_one');
$logo2 = get_field('logo_two');

// Videos:
$video_file = get_field('video_file');
$video_placeholder = get_field('video_placeholder_img');
?>

<?php if ($use_hero): ?>

  <div class="c-hero-element-d mediatype-<?= $media_type && $media_type != 'images' ? $media_type : 'images'; ?>">
    <div class="hero-inner">
      <div class="holder">

        <?php if ($media_type == 'video') : ?>

          <?php if ($video_file) : ?>
            <div class="bg-img">
              <video autoplay muted loop playsinline poster="<?= wp_get_attachment_image_url($video_placeholder['id'], 'full'); ?>">
                <source src="<?= esc_url($video_file['url']); ?>" type="<?= esc_attr($video_file['mime_type']); ?>">
              </video>
            </div>
          <?php endif; ?>

          <?php if ($logo1 || $logo2) : ?>
            <div class="logos">

              <?php if ($logo1) : ?>
                <div class="logo">
                  <?= wp_get_attachment_image($logo1['id'], 'full'); ?>
                </div>
              <?php endif; ?>

              <?php if ($logo2) : ?>
                <div class="logo">
                  <?= wp_get_attachment_image($logo2['id'], 'full'); ?>
                </div>
              <?php endif; ?>

            </div>
          <?php endif; ?>

        <?php else : ?>

          <?php if ($imgDesktop) : ?>
            <div class="bg-img">
              <?= wp_get_attachment_image($imgDesktop['id'], 'full'); ?>
            </div>
          <?php endif; ?>

          <?php if ($logo1 || $logo2) : ?>
            <div class="logos">

              <?php if ($logo1) : ?>
                <div class="logo">
                  <?= wp_get_attachment_image($logo1['id'], 'full'); ?>
                </div>
              <?php endif; ?>

              <?php if ($logo2) : ?>
                <div class="logo">
                  <?= wp_get_attachment_image($logo2['id'], 'full'); ?>
                </div>
              <?php endif; ?>

            </div>
          <?php endif; ?>

        <?php endif; ?>


      </div>
    </div>
  </div>

  <div class="c-hero-element-m mediatype-<?= $media_type && $media_type != 'images' ? $media_type : 'images'; ?>">
    <div class="hero-inner">

      <?php if ($media_type == 'video') : ?>

        <div class="holder">

          <?php if ($video_file) : ?>
            <div class="bg-img">
              <video autoplay muted loop playsinline poster="<?= wp_get_attachment_image_url($video_placeholder['id'], 'full'); ?>">
                <source src="<?= esc_url($video_file['url']); ?>" type="<?= esc_attr($video_file['mime_type']); ?>">
              </video>
            </div>
          <?php endif; ?>

          <?php if ($logo1 || $logo2) : ?>
            <div class="video-logos">

              <?php if ($logo1) : ?>
                <div class="logo">
                  <?= wp_get_attachment_image($logo1['id'], 'full'); ?>
                </div>
              <?php endif; ?>

              <?php if ($logo2) : ?>
                <div class="logo">
                  <?= wp_get_attachment_image($logo2['id'], 'full'); ?>
                </div>
              <?php endif; ?>

            </div>
          <?php endif; ?>

        </div>

      <?php else : ?>

        <div class="holder">

          <?php if ($img1) : ?>
            <div class="bg-img">
              <?= wp_get_attachment_image($img1['id'], 'full'); ?>
            </div>
          <?php endif; ?>

          <?php if ($imgm1) : ?>
            <div class="bg-img-m">
              <?= wp_get_attachment_image($imgm1['id'], 'full'); ?>
            </div>
          <?php endif; ?>

          <?php if ($logo1) : ?>
            <div class="logo">
              <?= wp_get_attachment_image($logo1['id'], 'full'); ?>
            </div>
          <?php endif; ?>

        </div>
        <div class="holder">

          <?php if ($img2) : ?>
            <div class="bg-img">
              <?= wp_get_attachment_image($img2['id'], 'full'); ?>
            </div>
          <?php endif; ?>

          <?php if ($imgm2) : ?>
            <div class="bg-img-m">
              <?= wp_get_attachment_image($imgm2['id'], 'full'); ?>
            </div>
          <?php endif; ?>

          <?php if ($logo2) : ?>
            <div class="logo">
              <?= wp_get_attachment_image($logo2['id'], 'full'); ?>
            </div>
          <?php endif; ?>

        </div>

      <?php endif; ?>

    </div>
  </div>
<?php endif; ?>