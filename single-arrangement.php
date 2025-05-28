<?php get_header(); ?>
<?php the_post(); ?>
<?php // movedo_grve_print_header_title( 'event' ); ?>

<div id="grve-event-title" class="grve-page-title grve-with-image grve-custom-size grve-bg-primary-1" data-height="15" style="min-height: 220px; height: 111.75px;">
			<div class="grve-wrapper clearfix" style="height: 111.75px; min-height: 220px;">
								<div class="grve-content grve-align-center-center show" data-animation="fade-in">
					<div class="grve-container">
						<div class="grve-title-content-wrapper grve-bg-none grve-align-center grve-content-large">
												
							<h5 style="color:#00000000000;"><?php echo get_field('dato'); ?> <? echo get_field('taggos'); ?>    </h5>
							<h1 class="grve-title grve-with-line grve-text-primary-4 grve-animate-fade-in"><span><?php echo get_the_title (); ?> </span></h1>
							
							
																		</div>
					</div>
				</div>
							</div>
			<div class="grve-background-wrapper"><div class="grve-bg-image grve-bg-center-center grve-bg-image-id-1010089"></div><div class="grve-bg-overlay show" style="background-color:rgba(26,54,59,1);"></div></div>
	
	</p>
	</p></div>
            
<?php movedo_grve_print_header_breadcrumbs( 'event' ); ?>
<?php movedo_grve_print_anchor_menu( 'event' ); ?>
        
<?php $navbar_layout = movedo_grve_option( 'post_nav_bar_layout', 'layout-2' ); ?>
<div class="grve-single-wrapper">
	<!-- CONTENT -->
<div id="grve-content" class="clearfix <?php echo movedo_grve_sidebar_class(); ?>">
		<div class="grve-content-wrapper">
			<!-- MAIN CONTENT -->
			<div id="grve-main-content">
				<div class="grve-main-content-wrapper clearfix">
				<?php
						get_template_part( 'content-arrangement', get_post_format() );
						//Post Pagination
						wp_link_pages();
				?>
					<div class="grve-container">
                    <h3 class="grve-element grve-title grve-align-center grve-h3" style="">
       <span>Arrangementer</span></h3>
                    <?php
					echo do_shortcode('[vc_row][vc_column][wppm post_type="arrangement" post_status="" num="50" taxonomy="category" terms="" hide_current_post="true" enable_slider="true" items="3" loop="true" animatein="" animateout="" imgwidth="600" imgheight="400" imgquality="85" xclass="no-border" hide_author="true" hide_date="true" ad_list="%5B%7B%7D%5D"][/vc_column][/vc_row]'); 
					?>
						<?php
							// Print Tags & Categories
							movedo_grve_print_post_tags();

							// Print About Author
							movedo_grve_print_post_about_author( 'overview' );

							//Print Comments
							if ( movedo_grve_visibility( 'post_comments_visibility' ) ) {
								comments_template();
							}

							if ( movedo_grve_visibility( 'post_related_visibility' ) ) {
								if ( 'layout-1' == $navbar_layout || 'layout-3' == $navbar_layout ) {
									$related_query = movedo_grve_get_related_posts();
									if ( !empty( $related_query ) ) {
										movedo_grve_print_related_posts( $related_query );
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<!-- END MAIN CONTENT -->
			<?php movedo_grve_set_current_view( 'event' ); ?>
			<?php get_sidebar(); ?>
		</div>
	</div>
	<!-- END CONTENT -->

	<?php
		//Posts Bar
		// movedo_grve_print_post_bar();
?>
</div>

<?php get_footer();
//Omit closing PHP tag to avoid accidental whitespace output errors.