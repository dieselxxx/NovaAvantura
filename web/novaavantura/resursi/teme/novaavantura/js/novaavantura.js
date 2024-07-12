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
    $('header form svg').click(function(event) {
        var form = $(this).closest("form");
        //console.log(form);
        form.submit();
    });
    $('form[data-oznaka="trazi_artikal"]').submit(function (odgovor) {

        let vrijednost = $('form[data-oznaka="trazi_artikal"] input[name="trazi"]').val();

        vrijednost = vrijednost.replace('/', ' ');

        window.location.href = '/artikli/sve kategorije/' + vrijednost;

        return false;

    });
    $('form[data-oznaka="trazi_artikal2"]').submit(function (odgovor) {

        let vrijednost = $('form[data-oznaka="trazi_artikal2"] input[name="trazi"]').val();

        vrijednost = vrijednost.replace('/', ' ');

        window.location.href = '/artikli/sve kategorije/' + vrijednost;

        return false;

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

    // meni checkbox
    $('main > .sadrzaj > .artikli > section > div.meni > section.brand input[type="checkbox"]').on('change', function() {
        $('main > .sadrzaj > .artikli > section > div.meni > section.brand input[type="checkbox"]').not(this).prop('checked', false);
        document.location.href = $(this).attr("data-url");
    });
    $('main > .sadrzaj > .artikli > section > div.meni > section.velicina input[type="checkbox"]').on('change', function() {
        $('main > .sadrzaj > .artikli > section > div.meni > section.velicina input[type="checkbox"]').not(this).prop('checked', false);
        document.location.href = $(this).attr("data-url");
    });

});