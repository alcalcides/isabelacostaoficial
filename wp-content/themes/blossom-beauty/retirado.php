<div class="header-b">
			<div class="container">
				<div id="primary-toggle-button"><i class="fa fa-bars"></i><?php esc_html_e( 'Navigation', 'blossom-beauty' ); ?></div>
				<nav id="site-navigation" class="main-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => 'blossom_feminine_primary_menu_fallback',
					) );
					?>
				</nav><!-- #site-navigation -->
				<?php if( $header_layout == 'two' ) : ?>
						<div class="tools">
							<div class="form-section">
								<span id="btn-search">
									<i class="fas fa-search"></i>
								</span>
								<div class="form-holder">
									<?php get_search_form(); ?>
								</div>
							</div>
							<?php if( blossom_feminine_is_woocommerce_activated() ) blossom_feminine_wc_cart_count(); ?>                    
						</div>                                               
				<?php endif; ?>
			</div>
		</div><!-- .header-b -->