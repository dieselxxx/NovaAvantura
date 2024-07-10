$_Cookie = function ($odgovor) {

    if ($odgovor === 'da') {

        $.ajax({
            type: 'POST',
            url: '/kolacic/gdpr',
            complete: function (odgovor) {
                $("#gdpr").remove();
            }
        });

    } else {

        window.history.back();

    }

};

// custom select
$('select').each(function(){
    var $this = $(this), numberOfOptions = $(this).children('option').length;

    $this.addClass('select-hidden');
    $this.wrap('<div class="select"></div>');
    $this.after('<div class="select-styled"></div>');

    var $styledSelect = $this.next('div.select-styled');
    $styledSelect.text($this.children('option').eq(0).text());

    var $list = $('<ul />', {
        'class': 'select-options'
    }).insertAfter($styledSelect);

    for (var i = 0; i < numberOfOptions; i++) {
        $('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
        if ($this.children('option').eq(i).is(':selected')){
            $('li[rel="' + $this.children('option').eq(i).val() + '"]').addClass('is-selected')
        }
    }

    var $listItems = $list.children('li');

    $styledSelect.click(function(e) {
        e.stopPropagation();
        $('div.select-styled.active').not(this).each(function(){
            $(this).removeClass('active').next('ul.select-options').hide();
        });
        $(this).toggleClass('active').next('ul.select-options').toggle();
    });

    $listItems.click(function(e) {
        e.stopPropagation();
        $styledSelect.text($(this).text()).removeClass('active');
        $this.val($(this).attr('rel'));
        $list.find('li.is-selected').removeClass('is-selected');
        $list.find('li[rel="' + $(this).attr('rel') + '"]').addClass('is-selected');
        $list.hide();
        //console.log($this.val());
    });

    $(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
    });

});

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

    // tabovi
    var acc = document.getElementsByClassName("accordion");
    var acc2 = document.getElementsByClassName("accordion2");
    var i;
    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    }
    for (i = 0; i < acc2.length; i++) {
        acc2[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    }

    // naslovna swiper
    var swiper = new Swiper(".rotator", {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 8000,
            disableOnInteraction: false
        },
        pagination: {
            el: ".swiper-pagination",
            type: "progressbar",
            clickable: true
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
    });

    // artikli select
    $('select[data-oznaka="redoslijed"]').on('change', function () {

        document.location.href = $(this).val();

    });

});