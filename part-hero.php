<?php
// Hero fields
$use_hero = get_field('use_hero');
$imgDesktop = get_field('image_desktop');

$img1 = get_field('image_one');
$img2 = get_field('image_two');
$imgm1 = get_field('image_mobile_one');
$imgm2 = get_field('image_mobile_two');

$logo1 = get_field('logo_one');
$logo2 = get_field('logo_two');
?>

<?php if ($use_hero): ?>
  <div class="c-hero-element-d">
    <div class="hero-inner">
      <div class="holder">
        <div class="bg-img">
          <?php echo wp_get_attachment_image($imgDesktop['id'], 'full'); ?>
        </div>
        <div class="logos">
          <div class="logo">
            <?php echo wp_get_attachment_image($logo1['id'], 'full'); ?>
          </div>
          <div class="logo">
            <?php echo wp_get_attachment_image($logo2['id'], 'full'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="c-hero-element-m">
    <div class="hero-inner">
      <div class="holder">
        <div class="bg-img">
          <?php echo wp_get_attachment_image($img1['id'], 'full'); ?>
        </div>
        <div class="bg-img-m">
          <?php echo wp_get_attachment_image($imgm1['id'], 'full'); ?>
        </div>
        <div class="logo">
          <?php echo wp_get_attachment_image($logo1['id'], 'full'); ?>
        </div>
      </div>
      <div class="holder">
        <div class="bg-img">
          <?php echo wp_get_attachment_image($img2['id'], 'full'); ?>
        </div>
        <div class="bg-img-m">
          <?php echo wp_get_attachment_image($imgm2['id'], 'full'); ?>
        </div>
        <div class="logo">
          <?php echo wp_get_attachment_image($logo2['id'], 'full'); ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>