<?php
/**
 * ADD ABILITY TO VIEW THUMBNAILS IN WP 4.0+
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('admin_init', 'bodhi_svgs_display_thumbs');

function bodhi_svgs_display_thumbs() {
	
	ob_start();

	add_action( 'shutdown', function() {
	    
	    $final = '';
	    $ob_levels = count( ob_get_level() );
	    
	    for ( $i = 0; $i < $ob_levels; $i++ ) {

	        $final .= ob_get_clean();

	    }

	    echo apply_filters( 'final_output', $final );

	}, 0 );

	add_filter( 'final_output', function( $content ) {

		$content = str_replace(
			'<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" />
				<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
			
			$content
		);

		$content = str_replace(
			'<# } else if ( \'image\' === data.type && data.sizes ) { #>',
			'<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<div class="centered">
					<img src="{{ data.url }}" class="thumbnail" draggable="false" />
				</div>
			<# } else if ( \'image\' === data.type && data.sizes ) { #>',
			
			$content
		);

		return $content;

	});
}