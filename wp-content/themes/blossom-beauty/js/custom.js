jQuery(document).ready(function($){    
    
    var rtl, mrtl;
    
    if( blossom_beauty_data.rtl == '1' ){
        rtl = true;
        mrtl = false;
    }else{
        rtl = false;
        mrtl = true;
    }

    //banner layout two
    $('.slider-layout-two').owlCarousel({
        loop       : true,
        nav        : true,
        items      : 1,
        dots       : false,
        autoplay   : true,
        rtl        : rtl,
        animateOut : blossom_beauty_data.animation,
        responsive : {
            1920: {
                margin: 130,
                stagePadding: 375
            },
            1440: {
                margin: 80,
                stagePadding: 150
            },           
            1200: {
                margin: 50,
                stagePadding: 85
            },
            768: {
                margin: 5,
                stagePadding: 85
            },
            0: {
                margin: 10,
                stagePadding: 30
            }
        }
    });  
});