jQuery(document).ready(function ($) {

	// Tabs
	$( ".inline-list" ).each( function() {
		$( this ).find( "li" ).each( function(i) {
			$( this).click( function(){
				$( this ).addClass( "current" ).siblings().removeClass( "current" )
				.parents( "#wpbody" ).find( "div.panel-left" ).removeClass( "visible" ).end().find( 'div.panel-left:eq('+i+')' ).addClass( "visible" );
				return false;
			} );
		} );
	} );


	// Scroll to anchor
	$( ".anchor-nav a, .toc a" ).click( function(e) {
		e.preventDefault();

		var href = $( this ).attr( "href" );
		$( "html, body" ).animate( {
			scrollTop: $( href ).offset().top - 50
		}, 'slow', 'swing' );
	} );


	// Back to top links
	$('.getting-started #help-panel h3').append( $("<a class='back-to-top' href='#panel'><i class='fa fa-angle-up'></i> Back to top</a>") );

	//faq toggle
	$('.toggle-block:not(.active) .toggle-content').hide();
	$('.toggle-block .toggle-title').click(function(){
		$(this).parent('.toggle-block').siblings().removeClass('active');
		$(this).parent('.toggle-block').addClass('active');
		$(this).parent('.toggle-block').siblings().children('.toggle-content').slideUp();
		$(this).siblings('.toggle-content').slideDown();
	});
});