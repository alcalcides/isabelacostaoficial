<?php
/**
 * Functions of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_Functions {
// JavaScript Minifier
	
		function bten_minify_js( $input ) {
		    if(trim($input) === "") return $input;
		    return preg_replace(
		        array(
		            // Remove comment(s)
		            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
		            // Remove white-space(s) outside the string and regex
		            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
		            // Remove the last semicolon
		            '#;+\}#',
		            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
		            '#([\{,])([\'])(\d+|[a-z_]\w*)\2(?=\:)#i',
		            // --ibid. From `foo['bar']` to `foo.bar`
		            '#([\w\)\]])\[([\'"])([a-z_]\w*)\2\]#i',
		            // Replace `true` with `!0`
		            '#(?<=return |[=:,\(\[])true\b#',
		            // Replace `false` with `!1`
		            '#(?<=return |[=:,\(\[])false\b#',
		            // Clean up ...
		            '#\s*(\/\*|\*\/)\s*#'
		        ),
		        array(
		            '$1',
		            '$1$2',
		            '}',
		            '$1$3',
		            '$1.$3',
		            '!0',
		            '!1',
		            '$1'
		        ),
		    $input);
		}
	

	
		function bten_minify_css( $input ) {
		    if(trim($input) === "") return $input;
		    // Force white-space(s) in `calc()`
		    if(strpos($input, 'calc(') !== false) {
		        $input = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#', function($matches) {
		            return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
		        }, $input);
		    }
		    return preg_replace(
		        array(
		            // Remove comment(s)
		            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
		            // Remove unused white-space(s)
		            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
		            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
		            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
		            // Replace `:0 0 0 0` with `:0`
		            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
		            // Replace `background-position:0` with `background-position:0 0`
		            '#(background-position):0(?=[;\}])#si',
		            // Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
		            '#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
		            // Minify string value
		            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
		            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
		            // Minify HEX color code
		            '#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
		            // Replace `(border|outline):none` with `(border|outline):0`
		            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
		            // Remove empty selector(s)
		            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
		            '#\x1A#'
		        ),
		        array(
		            '$1',
		            '$1$2$3$4$5$6$7',
		            '$1',
		            ':0',
		            '$1:0 0',
		            '.$1',
		            '$1$3',
		            '$1$2$4$5',
		            '$1$2$3',
		            '$1:0',
		            '$1$2',
		            ' '
		        ),
		    $input);
		}

	/**
     * Retrieves the image field.
     *  
     * @link https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
     */
    function blossomthemes_email_newsletter_companion_get_image_field( $id, $name, $image, $label ){
        $output = '';
        $output .= '<div class="widget-upload">';
        $output .= '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br/>';
        $output .= '<input id="' . esc_attr( $id ) . '" class="bten-upload" type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $image ) . '" placeholder="' . __('No file chosen', 'blossomthemes-email-newsletter') . '" />' . "\n";
        if ( function_exists( 'wp_enqueue_media' ) ) {
            if ( $image == '' ) {
                $output .= '<input id="upload-' . esc_attr( $id ) . '" class="bten-upload-button button" type="button" value="' . __('Upload', 'blossomthemes-email-newsletter') . '" />' . "\n";
            } else {
                $output .= '<input id="upload-' . esc_attr( $id ) . '" class="bten-upload-button button" type="button" value="' . __('Change', 'blossomthemes-email-newsletter') . '" />' . "\n";
            }
        } else {
            $output .= '<p><i>' . __('Upgrade your version of WordPress for full media support.', 'blossomthemes-email-newsletter') . '</i></p>';
        }

        $output .= '<div class="bten-screenshot" id="' . esc_attr( $id ) . '-image">' . "\n";

        if ( $image != '' ) {
            $remove = '<a href="#" class="bten-remove-image">'.__('Remove Image','blossomthemes-email-newsletter').'</a>';
            $attachment_id = $image;
            $image_array = wp_get_attachment_image_src( $attachment_id, 'full');
            if ( $image_array[0] ) {
                $output .= '<img src="' . esc_url( $image_array[0] ) . '" alt="" />' . $remove;
            } else {
                // Standard generic output if it's not an image.
                $output .= '<small>' . __( 'Please upload valid image file.', 'blossomthemes-email-newsletter' ) . '</small>';
            }     
        }
        $output .= '</div></div>' . "\n";
        
        echo $output;
    }

     /**
     * Get an attachment ID given a URL.
     * 
     * @param string $url
     *
     * @return int Attachment ID on success, 0 on failure
     */
    function blossomthemes_email_newsletter_get_attachment_id( $url ) {
        $attachment_id = 0;
        $dir = wp_upload_dir();
        if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
            $file = basename( $url );
            $query_args = array(
                'post_type'   => 'attachment',
                'post_status' => 'inherit',
                'fields'      => 'ids',
                'meta_query'  => array(
                    array(
                        'value'   => $file,
                        'compare' => 'LIKE',
                        'key'     => '_wp_attachment_metadata',
                    ),
                )
            );
            $query = new WP_Query( $query_args );
            if ( $query->have_posts() ) {
                foreach ( $query->posts as $post_id ) {
                    $meta = wp_get_attachment_metadata( $post_id );
                    $original_file       = basename( $meta['file'] );
                    $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
                    if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                        $attachment_id = $post_id;
                        break;
                    }
                }
            }
        }
        return $attachment_id;
    }
}
