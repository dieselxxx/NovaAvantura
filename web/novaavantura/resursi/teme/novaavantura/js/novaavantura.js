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

ArtikalPlusMinus = function (element, $vrsta) {

    let staraVrijednost = $(element).parent().find('input[name="vrijednost"]').val();

    let pakiranje = $(element).parent().find('input[name="vrijednost"]').data("pakiranje");

    let maxpakiranje = $(element).parent().find('input[name="vrijednost"]').data("maxpakiranje");

    let novaVrijednost;

    if ($vrsta === 'plus') {

        if (staraVrijednost >= maxpakiranje) {

            novaVrijednost = maxpakiranje;

        } else if (staraVrijednost > 0) {

            novaVrijednost = parseFloat(staraVrijednost) + pakiranje;

        } else {

            novaVrijednost = pakiranje;

        }

    } else if ($vrsta === 'minus') {

        if (staraVrijednost > pakiranje) {

            novaVrijednost = parseFloat(staraVrijednost) - pakiranje;

        } else {

            novaVrijednost = pakiranje;

        }

    }

    $(element).parent().find('input[name="vrijednost"]').val(novaVrijednost);

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
    $('header form label svg').click(function(event) {
        var form = $(this).closest("form");
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
    if (screen.width >= 1200) {$("main > .sadrzaj > .artikli > section > div.meni > section .panel").css("max-height","initial");}
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
        document.location.href = $(this).attr("data-url");
    });
    /*$('main > .sadrzaj > .artikli > section > div.meni > section.velicina input[type="checkbox"]').on('change', function() {
        $('main > .sadrzaj > .artikli > section > div.meni > section.velicina input[type="checkbox"]').not(this).prop('checked', false);
        document.location.href = $(this).attr("data-url");
    });*/
    $('main > .sadrzaj > .artikli > section > div.meni > section.velicina input[type="checkbox"]').change(function() {
        if (this.checked) {
            document.location.href = $(this).attr("data-url");
        } else {
            document.location.href = $(this).attr("data-url2");
        }
    });


    // meni cijena cijena_min
    $('main > .sadrzaj > .artikli > section > div.meni > section.cijena input').change(function() {
        let url = window.location.pathname.split('/');
        url[4] = $('main > .sadrzaj > .artikli > section > div.meni > section.cijena .panel input[name="cijena_min"]').val();
        url[5] = $('main > .sadrzaj > .artikli > section > div.meni > section.cijena .panel input[name="cijena_max"]').val();
        if ($.isEmptyObject(url[3])) {url[3] = 'svi artikli'}
        document.location.href = url.join('/');
    });

    // meni zatvori
    $('header .meni .mobile-meni-gumb').focus(function(event) {
        $("header .meni .mobile-meni-gumb_zatvori").toggle();
    });
    $('header .meni .mobile-meni-gumb_zatvori').click(function(event) {
        $("header .meni .mobile-meni-gumb_zatvori").toggle();
    });

    // narudzba tvrtka
    $('input[name="r1"]').change(function() {
        if (this.checked) {
            $("section.tvrtka").show();
        } else {
            $("section.tvrtka").hide();
        }
    });

    // scroll text
    const scrollers = document.querySelectorAll('.scroller');
    if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        addAnimation();
    }
    function addAnimation() {
        scrollers.forEach(scroller => {
            scroller.setAttribute('data-animated', true);

            const scrollerInner = scroller.querySelector('.scroller__inner');
            const scrollerContent = Array.from(scrollerInner.children);

            scrollerContent.forEach(item => {
                const duplicatedItem = item.cloneNode(true);
                duplicatedItem.setAttribute("aria-hidden", true);
                scrollerInner.appendChild(duplicatedItem);
            })
        });
    }

});