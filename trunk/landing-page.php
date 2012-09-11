<?php
/**
 * Template Name: Maandumisleht
 * Description: Maandumislehe mall (landing page)
 */

get_header(); ?>

<div class="main-content landing-page">

	<div class="page-inside-content">
		<?php while ( have_posts() ) : the_post(); ?>
		
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->
			
				<div class="entry-content">
					<?php
					if ( has_landing_thumbnail() ) {
						the_landing_thumbnail('full');
					} 
					?>
					<?php the_content(); ?>
					
					<div id="landing-meta">
						<div class="landing-sais-button">
							<a href="http://www.sais.ee/" title="SAIS"><img src="<?php echo getFileURL('/images/sais-button.png');?>"/></a>
						</div>
						<?php
							do_shortcode('[hk_reminder flag="'.$post->post_name.'"]');
						?>
						<div class="landing-reminder-button" id="landing-reminder-button">
							<div>Soovid õppida valitud erialal?</div>
							<a href="#" id="subscribeReminder">TELLI MEELESPEA</a>
						</div>
						
						<?php 
							if ( has_youtube_video() ) {
						?>
							<div id="playerWrapper" style="display:none">
								<div id="player"></div>
							</div>
							<div class="landing-video-button" id="landing-video-button">
								<div>Kuula mida tudengid arvavad.</div>
								<a href="#" id="playLandingVideo">VAATA KLIPPI</a>
							</div>
						<?php 
							}
						?>
					</div>
				</div><!-- .entry-content -->
			</article><!-- #post-<?php the_ID(); ?> -->
		
		<?php endwhile; // end of the loop. ?>

	<?php 
		//This will output menu, if any assigned, otherwise nothing
		getLandingPageMenu( get_the_ID() );
	?>
	</div>
</div><!-- #primary -->

<?php get_footer(); ?>