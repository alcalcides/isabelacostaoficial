<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Blossom_Feminine
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<section class="not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Uh-Oh...', 'blossom-feminine' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'The page you are looking for may have been moved, deleted, or possibly never existed.', 'blossom-feminine' ); ?></p>
                    <h2><?php esc_html_e( '404', 'blossom-feminine' ); ?></h2>
                    <a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e( 'TAKE ME TO THE HOME PAGE', 'blossom-feminine' ); ?></a>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</section><!-- .not-found -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
