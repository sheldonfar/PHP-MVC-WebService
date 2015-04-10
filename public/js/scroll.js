$(document).ready(function(){
    var scrollBottom = $(window).scrollTop() + $(window).height();

    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }


        if ($(this).scrollTop() < 15000) {
            $('.scrollToBottom').fadeIn();
        } else {
            $('.scrollToBottom').fadeOut();
        }
    });

    $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });

    $(".scrollToTop").hover(function(){
            $(this).stop().animate({opacity:'2.0'})},
            function(){$(this).stop().animate({opacity:'.7'})}
    );


    $('.scrollToBottom').click(function(){
        $('html, body').animate({scrollTop : $('body').height()},800);
        return false;
    });

    $(".scrollToBottom").hover(function(){
            $(this).stop().animate({opacity:'2.0'})},
        function(){$(this).stop().animate({opacity:'.7'})}
    );

});
