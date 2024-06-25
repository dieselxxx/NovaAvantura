$(document).ready(function () {

    // skrolanje zaglavlja
    $(window).scroll(function(e){
        $el = $('header');
        $el.toggleClass('skrolanje', $(this).scrollTop() > 200);
    });

});