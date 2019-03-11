<?php
global $post;
?>
<h4><?php _e( 'Shortcode', 'wen-logo-slider' ); ?></h4>
<p><?php _e( 'Copy and paste this shortcode directly into any WordPress post or page.', 'wen-logo-slider' ); ?></p>
<input type="text" class="large-text code" readonly="readonly" value='<?php echo '[BTEN id="'.$post->ID.'"]'; ?>' />

<h4><?php _e( 'Template Include', 'wen-logo-slider' ); ?></h4>
<p><?php _e( 'Copy and paste this code into a template file to include the slider within your theme.', 'wen-logo-slider' ); ?></p>
<input type="text" class="large-text code" readonly="readonly" value="&lt;?php echo do_shortcode(&quot;[BTEN id='<?php echo $post->ID; ?>']&quot;); ?&gt;" />
