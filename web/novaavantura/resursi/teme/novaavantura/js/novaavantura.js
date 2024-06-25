$(document).ready(function () {

    // skrolanje zaglavlja
    $(window).scroll(function(e){
        $el = $('header');
        $el.toggleClass('skrolanje', $(this).scrollTop() > 200);
    });

    // tražilica
    $('header a.trazi').click(function(event) {
        $("#trazi").animate({
            height: 'toggle'
        });
        $("header > .pozadina").toggle();
    });

});