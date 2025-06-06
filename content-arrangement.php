<?php
/**
 * The default post template
 */
?>

<style type="text/css">
#grve-main-content .grve-main-content-wrapper, #grve-sidebar {
  padding-top: 0px;
  padding-bottom: 90px;
  background:#fff;
}

.grve-section {
	background:#fff !important ;
}

.grve-leader-text blockquote p{
  font-family: Georgia, serif;
  font-weight: 400;
  font-style: italic;
  font-size: 24px;
  line-height: 40px;
  text-transform: none;
  letter-spacing: 0px;
}
li::marker {
	color:#104b65;
}
.fa-map-marker:hover {
	color:#ce496e;
}

 .ribbon {
    font-size:24px;
	font-family: GT-Pressura-Bold;
    color: #333;
    text-align: center;
    -webkit-transform: rotate(-45deg);
    -moz-transform:    rotate(-45deg);
    -ms-transform:     rotate(-45deg);
    -o-transform:      rotate(-45deg);
    position: relative;
    padding: 14px 0;
    top: 30px;
    left: -60px;
    width: 240px;
    background-color: #ff8272 !important;
    color: #fff;
  }


</style>
 
<?php
if ( is_singular() ) {
	$movedo_grve_disable_media = movedo_grve_post_meta( '_movedo_grve_disable_media' ); 
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('grve-single-post'); ?> itemscope itemType="http://schema.org/BlogPosting">	
   		
        

        <div id="grve-single-content">
        
			<?php //movedo_grve_print_post_simple_title(); ?>
			<?php movedo_grve_print_post_structured_data(); ?>
            
            
            <div class="grve-section grve-row-section grve-fullwidth-background grve-padding-top-6x grve-padding-bottom-6x grve-bg-none" >
            
            <div class="grve-container">
            <div class="grve-row grve-bookmark grve-columns-gap-0">
            <div class="grve-column wpb_column grve-column-1">
            <div class="grve-column-wrapper vc_custom_1657007730655" style="padding-top:8%; padding-bottom:8%;">
            </div></div></div>
            
            </div>
            
            <div class="grve-background-wrapper">
            
        
   
  
            <div class="grve-bg-image grve-bg-center-center grve-bg-image-id-1010089 show" style="background-image: url(
			<?php if( get_field('bilde2') ) {
				 echo get_field('bilde2');
				 } else {
			   echo wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
			   }
			   ?> );"><?php if( get_field('status') ): ?>
         <div class="ribbon">Utsolgt!</div>
		<?php endif; ?>  
            </div>
            
            </div>
                       
            
            </div>            
			<div itemprop="articleBody">                      

       <div class="grve-section grve-row-section grve-fullwidth-background grve-padding-top-3x grve-padding-bottom-3x grve-bg-none">
       <div class="grve-container ">
       <div class="grve-row grve-bookmark grve-columns-gap-30 grve-rtl-columns-reverse" >
       
       <div class="grve-column wpb_column grve-column-1-4 grve-bg-none">
       <div class="grve-column-wrapper">
       <div class="grve-container">
       <h3 class="grve-element grve-title grve-align-left grve-h2" style="">
       <span>FAKTA</span></h3>
       
       <div class="grve-element grve-text grve-leader-text">
	
	<?php /*?><?php if( get_field('status') ): ?>
         <p style="color:#ff8272;"><strong>UTSOLGT! </strong></p> 
		<?php endif; ?><?php */?>
		
	<?php		
   if( get_field('dato_periode') ) {  ?>
   <p><strong>Dato</strong> <br />
	<?php echo get_field('dato_periode'); ?></p>     

<?php } else { ?>
	
	 <?php if( get_field('dato') ): ?>
        <p><strong>Dato</strong> <br /><?php echo get_field('dato'); ?></p>
		<?php endif; ?>
		
	<?php } ?>


       <?php /*?> <?php if( get_field('dato') ): ?>
        <p><strong>Dato</strong> <br /><?php echo get_field('dato'); ?></p>
		<?php endif; ?><?php */?>
            
               
		<?php if( get_field('tidspunkt') ): ?>
        <p><strong>Tidspunkt</strong><br /> <?php echo get_field('tidspunkt'); ?></p>
		<?php endif; ?>        
        
		<?php if( get_field('sted') ): ?>
         <strong>Sted</strong> <br /> <?php echo get_field('sted'); ?>
         <a href="https://www.google.com/maps/place/<?php echo get_field('sted'); ?>" target="_blank"> 
         <i class="fa fa-map-marker" style="font-size:26px;"></i>
         </a>
		<p> </p>  <?php endif; ?>
        
        
		<?php if( get_field('pris') ): ?>
         <p><strong>Pris </strong><br /> <?php echo get_field('pris'); ?></p> 
		<?php endif; ?>
		
        
        
<?php
if( get_field('status') )  {
   ?>
   <button disabled type="button" class="grve-btn grve-btn-large grve-square grve-bg-primary-3 grve-bg-hover-primary-4">
   <span>Utsolgt!</span></button>

<?php } else { ?>
            <?php if( get_field('pamelding') ): ?>
            <a href="<?php echo get_field('pamelding') ?>" target="_blank" rel="noopener noreferrer" class="grve-btn grve-btn-large grve-square grve-bg-primary-6 grve-bg-hover-primary-3">
            <span>Påmelding</span></a>
            <?php endif; ?>
            <?php if( get_field('pamelding_epost') ): ?>
            <a href="mailto:<?php echo get_field('pamelding_epost') ?>?subject=Matfest 2023" target="_blank" rel="noopener noreferrer" class="grve-btn grve-btn-large grve-square grve-bg-primary-6 grve-bg-hover-primary-3">
            <span>Send epost</span></a>
            <?php endif; ?>
            
            <?php } ?>
          
            
            
            
            <?php // get_field('bilde2') ?>
            </div>
			<br />
			Del på Facebook
			<br />
			<?php echo do_shortcode('[movedo_social social_facebook="yes"]'); ?>
            </div>
	</div></div>
    
    <div class="grve-column wpb_column grve-column-3-4 grve-padding-bottom-2x">
    <div class="grve-column-wrapper">    
  
    <div class="grve-element grve-text grve-leader-text">    
	 <?php /*?><h3 class="grve-element grve-title grve-align-left grve-h3">
       <span><?php echo get_the_title (); ?> </span></h3><?php */?>
				     <?php /*?> <?php the_post_thumbnail( $image_size ); ?><br /><?php */?>
 <?php the_content(); ?>
 

		</div>
	</div>
    </div>
    </div>
    </div>
   
   
    </div>
    
	</article>
         
                          
<?php
} else {

	$blog_mode = movedo_grve_option( 'blog_mode', 'large' );

	$post_style = movedo_grve_post_meta( '_movedo_grve_post_standard_style' );
	$bg_mode = false;

	if ( ( 'masonry' == $blog_mode || 'grid' == $blog_mode ) && 'movedo' == $post_style ) {
		$bg_mode = true;
	}
	if ( $bg_mode ) {
		$movedo_grve_post_class = movedo_grve_get_post_class("grve-style-2");
		$bg_color = movedo_grve_post_meta( '_movedo_grve_post_standard_bg_color', 'black' );
		$bg_opacity = movedo_grve_post_meta( '_movedo_grve_post_standard_bg_opacity', '70' );
		$bg_options = array(
			'bg_color' => $bg_color,
			'bg_opacity' => $bg_opacity,
		);
	} else {
		$movedo_grve_post_class = movedo_grve_get_post_class();
	}

?>

	<!-- Article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class( $movedo_grve_post_class ); ?> itemscope itemType="http://schema.org/BlogPosting">
		<?php do_action( 'movedo_grve_inner_post_loop_item_before' ); ?>
		<?php if ( $bg_mode ) { ?>
		<?php movedo_grve_print_post_bg_image_container( $bg_options ); ?>
		<?php } else { ?>
		<?php movedo_grve_print_post_feature_media( 'image' ); ?>
		<?php } ?>
        
		<div class="grve-post-content-wrapper">
			<div class="grve-post-content" >
				<?php movedo_grve_print_post_meta_top(); ?>
				<?php movedo_grve_print_post_structured_data(); ?>
				<div itemprop="articleBody">
					<?php movedo_grve_print_post_excerpt(); ?>
				</div>
			</div>
		</div>
		<?php do_action( 'movedo_grve_inner_post_loop_item_after' ); ?>
	</article>
    
	<!-- End Article -->

<?php

}

//Omit closing PHP tag to avoid accidental whitespace output errors.
