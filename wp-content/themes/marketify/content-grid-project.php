<?php
/**
 * @package Marketify
 */

global $post;

$client = get_post_meta( $post->ID, '_client', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'content-grid-download' . ( ! $client ? ' no-client' : '' ) ); ?>>
	<div class="entry-image gm-test2">
		<div class="overlay">
			<?php do_action( 'marketify_project_content_image_overlay_before' ); ?>

			<div class="actions">
				<a href="<?php the_permalink(); ?>" rel="bookmark" class="button"><?php _e( 'Project Details', 'marketify' ); ?></a>

				<?php if ( $client ) : ?>
				<strong class="item-price">
					<span><?php printf( 'Client: %s', esc_attr( $client ) ); ?></span>
				</strong>
				<?php endif; ?>
			</div>

			<?php do_action( 'marketify_project_content_image_overlay_after' ); ?>
		</div>

		<?php the_post_thumbnail( 'thumbnail' ); ?>
	</div>

	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</header><!-- .entry-header -->
</article><!-- #post-## -->
