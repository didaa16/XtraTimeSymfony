"use strict"; // Start of use strict

if ( $('.customizer-box button.toggler').length ) {
	$('.customizer-box button.toggler').on('click', function () {
		$('.customizer-box').toggleClass('off on');
	});
};


// function for style switcher
function swithcerMenu () {
  if ($('.customizer-box').length) {

    $('.pattern-box').on('click', function () {
        $('.pattern-box').each(function () {
            $('html').removeClass( $(this).data('pattern-name') );
        });
        var className = $(this).data('pattern-name');
        $('html').addClass(className);
        console.log(className);
    });
    $(".customizer-box .navigation-switch").on('click', function(){
        var checker = $(this).find('input');
        if (checker.is(':checked')) {
            $('.header').removeClass('stricky-fixed');    
            $('.header').addClass('static');    
        }else {
            $('.header').addClass('stricky-fixed');    
            $('.header').removeClass('static');    
        };
        
    });

    $(".customizer-box #boxed").on('click', function(){
        Cookies.remove('boxed-layout');
        Cookies.set('boxed-layout', 'boxed', { expires: 365, path: '/' });
        $('body').removeClass('full-width');
        $('body').addClass('boxed');
    });
    $(".customizer-box #full-box").on('click', function(){
        Cookies.remove('boxed-layout');
        $('body').removeClass('boxed');
        $('body').addClass('full-width');
        Cookies.set('boxed-layout', 'full-width', { expires: 365, path: '/' });
    });

    $('#styleOptions').styleSwitcher({
    	hasPreview: false,
        fullPath: 'skins/color-files/css/',
         cookie: {
          expires: 999,
          isManagingLoad: true
        }
    });
    

    // chnage the theme related img/logo
    $('#styleOptions .color1').on('click',function() {      
        
        Cookies.remove('logo-img-light');
        Cookies.remove('logo-img-dark');
        Cookies.remove('banner-1');
        Cookies.remove('banner-2');
        Cookies.remove('home-offer');
        Cookies.remove('map-skin');

        Cookies.set('logo-img-light', 'skins/color-files/img/color1/logo-light.png', { expires: 365, path: '/' });
        Cookies.set('logo-img-dark','skins/color-files/img/color1/logo-dark.png', { expires: 365, path: '/' });
        Cookies.set('banner-1','skins/color-files/img/color1/home-banner-1.png', { expires: 365, path: '/' });
        Cookies.set('banner-2','skins/color-files/img/color1/home-banner-2.png', { expires: 365, path: '/' });
        Cookies.set('home-offer','skins/color-files/img/color1/home-offer.jpg', { expires: 365, path: '/' });
        Cookies.set('map-skin','skin-1', { expires: 365, path: '/' });
    });

    // chnage the theme related img/logo
    $('#styleOptions .color2').on('click',function() {      
        
        Cookies.remove('logo-img-light');
        Cookies.remove('logo-img-dark');
        Cookies.remove('banner-1');
        Cookies.remove('banner-2');
        Cookies.remove('home-offer');
        Cookies.remove('map-skin');

        Cookies.set('logo-img-light', 'skins/color-files/img/color2/logo-light.png', { expires: 365, path: '/' });
        Cookies.set('logo-img-dark','skins/color-files/img/color2/logo-dark.png', { expires: 365, path: '/' });
        Cookies.set('banner-1','skins/color-files/img/color2/home-banner-1.png', { expires: 365, path: '/' });
        Cookies.set('banner-2','skins/color-files/img/color2/home-banner-2.png', { expires: 365, path: '/' });
        Cookies.set('home-offer','skins/color-files/img/color2/home-offer.jpg', { expires: 365, path: '/' });
        Cookies.set('map-skin','skin-2', { expires: 365, path: '/' });
    });
    // chnage the theme related img/logo
    $('#styleOptions .color3').on('click',function() {      
        
        Cookies.remove('logo-img-light');
        Cookies.remove('logo-img-dark');
        Cookies.remove('banner-1');
        Cookies.remove('banner-2');
        Cookies.remove('home-offer');
        Cookies.remove('map-skin');

        Cookies.set('logo-img-light', 'skins/color-files/img/color3/logo-light.png', { expires: 365, path: '/' });
        Cookies.set('logo-img-dark','skins/color-files/img/color3/logo-dark.png', { expires: 365, path: '/' });
        Cookies.set('banner-1','skins/color-files/img/color3/home-banner-1.png', { expires: 365, path: '/' });
        Cookies.set('banner-2','skins/color-files/img/color3/home-banner-2.png', { expires: 365, path: '/' });
        Cookies.set('home-offer','skins/color-files/img/color3/home-offer.jpg', { expires: 365, path: '/' });
        Cookies.set('map-skin','skin-3', { expires: 365, path: '/' });
    });
    // chnage the theme related img/logo
    $('#styleOptions .color4').on('click',function() {    	
    	
    	Cookies.remove('logo-img-light');
        Cookies.remove('logo-img-dark');
        Cookies.remove('banner-1');
    	Cookies.remove('banner-2');
        Cookies.remove('home-offer');
    	Cookies.remove('map-skin');

    	Cookies.set('logo-img-light', 'skins/color-files/img/color4/logo-light.png', { expires: 365, path: '/' });
        Cookies.set('logo-img-dark','skins/color-files/img/color4/logo-dark.png', { expires: 365, path: '/' });
        Cookies.set('banner-1','skins/color-files/img/color4/home-banner-1.png', { expires: 365, path: '/' });
    	Cookies.set('banner-2','skins/color-files/img/color4/home-banner-2.png', { expires: 365, path: '/' });
        Cookies.set('home-offer','skins/color-files/img/color4/home-offer.jpg', { expires: 365, path: '/' });
    	Cookies.set('map-skin','skin-4', { expires: 365, path: '/' });
    });

     // var $imgsrc = $.cookie('mycookieimg');    
    $('.header .menuzord-brand img').attr('src', Cookies.get('logo-img-light'));
    $('.header-v2.header .menuzord-brand img').attr('src', Cookies.get('logo-img-dark'));
    $('.logo-h3 a img').attr('src', Cookies.get('logo-img-dark'));
    $('.footer-logo a img').attr('src', Cookies.get('logo-img-light'));
    $('#skin-banner-1').attr('src', Cookies.get('banner-1'));
    $('#skin-banner-2').attr('src', Cookies.get('banner-2'));
    $('img.offer-home-two').attr('src', Cookies.get('home-offer'));
	$('.call-to-action-widget img').attr('src', Cookies.get('home-offer'));
    $('body').addClass(Cookies.get('boxed-layout'));
	$('.google-map').addClass(Cookies.get('map-skin'));
	console.log(Cookies.get());

  };
}
swithcerMenu();