<div class="tools" id="customizedByAlcides">
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