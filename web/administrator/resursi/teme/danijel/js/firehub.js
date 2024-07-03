$(document).ready(function () {

    /**
     * Navigacija status.
     */
    let $lokalnaPohrana = new LokalnaPohrana();
    let NavigacijaStatus = $lokalnaPohrana.Procitaj("NavigacijaStatus");

    if (NavigacijaStatus != null) {

        if (NavigacijaStatus === 'zatvoren') {

            $('body').addClass('zatvoren');

        }
    }
    $('header .navigacija_gumb').on('click', function (event) {

        if ($('body').hasClass('zatvoren')) {

            $lokalnaPohrana.Umetni('NavigacijaStatus', 'otvoren');
            $('body').removeClass('zatvoren');

        } else {

            $lokalnaPohrana.Umetni('NavigacijaStatus', 'zatvoren');
            $('body').addClass('zatvoren');

        }

    });

    /**
     * Zaglavlje profil.
     */
    $("header nav").on("click", function() {

        event.stopPropagation();

        $(this).find('ul li ul.podmeni').toggle(
            function() {
                $(this).animate({}, 500)
            }
        )

    });
    $(document).on("click", function() {

        $('header nav ul li ul.podmeni').hide(
            function() {
                $(this).animate({}, 500)
            }
        );

    });
    $('header nav ul li ul.podmeni li.treci_level').hover(

        function () {
            $(this).find('ul').show(
                function() {
                    $(this).animate({}, 500)
                }
            );
        }, function(){
            $(this).find('ul').hide(
                function() {
                    $(this).animate({}, 500)
                }
            );
        }

    );

    /**
     * Navigacija.
     */
    let url = window.location.pathname.split('/');
    if (!url[1]) {var link = '';}
    else if (!url[2]) {var link = url[1];}
    else {var link = url[1] + '/' + url[2]}
    var link = $('a[href$="' + link + '"]');
    link.closest("li").addClass("aktivan").parent("ul").parent("li").addClass("aktivan otvoren").parent("ul").parent("li").addClass("aktivan otvoren");

    $("aside nav li.podmeni > a").on("click", function() {

        $(this).removeAttr("href");
        let roditelj = $(this).parent("li");

        if (roditelj.hasClass("otvoren")) {

            roditelj.removeClass("otvoren");
            roditelj.find("li").removeClass("otvoren");
            roditelj.find("ul").slideUp(200);

        } else {

            roditelj.addClass("otvoren");
            roditelj.children("ul").slideDown(200);
            roditelj.siblings("li").children("ul").slideUp(200);
            roditelj.siblings("li").removeClass("otvoren");
            roditelj.siblings("li").find("li").removeClass("otvoren");
            roditelj.siblings("li").find("ul").slideUp(200);

        }

    });

    /**
     * Prijava.
     */
    $('form[data-oznaka="prijava"]').submit(function (odgovor) {

        odgovor.preventDefault();

        let podatci = $('form[data-oznaka="prijava"]').serializeArray();

        // dialog prozor
        let dialog = new Dialog();

        $.ajax({
            type: 'POST',
            url: '/administrator/prijava/autorizacija',
            dataType: 'json',
            data: podatci,
            beforeSend: function () {
                Dialog.dialogOtvori();
                dialog.sadrzaj(Loader_Krug);
            },
            success: function (odgovor) {
                Dialog.dialogOcisti();
                dialog.naslov('Poruka');
                dialog.sadrzaj(odgovor.Poruka);
                dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');

                if (odgovor.Validacija === 'da') {
                    //location.reload();
                    window.location.href = "/administrator/";
                }
            },
            error: function () {
                Dialog.dialogOcisti();
                dialog.naslov('Greška');
                dialog.sadrzaj('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
                dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            },
            complete: function (odgovor) {
                //
            }
        });

        return false;

    });

});

/**
 * Spremi slike.
 */
$(function() {

    $("body").on('submit', 'form.slika', function() {

        let oznaka = $(this).data("oznaka");

        $_SpremiSlike('form[data-oznaka="' + oznaka + '"]');

        return false;

    }).on('change','form.slika input[type="file"]', function() {

        let oznaka = $(this).closest('form').data("oznaka");

        $('form[data-oznaka="' + oznaka + '"]').submit();

        return false;

    });

});
$_SpremiSlike = function ($url) {

    // dialog prozor
    let dialog = new Dialog();

    $($url).ajaxSubmit({
        beforeSend: function() {
            Dialog.dialogOtvori(false);
            dialog.naslov('Dodajem sliku');
            dialog.sadrzaj('' +
                '<div class="progres" style="display: block;">\
                    <div class="bar" style="width: 0%;"></div>\
                    <div class="postotak">0%</div>\
                </div>'
            );
        },
        uploadProgress: function(event, position, total, postotakZavrseno) {
            $('#dialog .sadrzaj .bar').width(postotakZavrseno + '%');
            $('#dialog .sadrzaj .postotak').html(postotakZavrseno + '%');
        },
        success: function(odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Dodajem sliku');
            dialog.sadrzaj(odgovor.Poruka);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">U redu</button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.sadrzaj('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function(odgovor) {
            location.reload();
        }
    });

    return false;

};

/**
 * Odjavi se.
 */
$_Odjava = function () {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'POST',
        url: '/administrator/odjava',
        dataType: 'json',
        beforeSend: function () {
            Dialog.dialogOtvori(false);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Poruka');
            dialog.sadrzaj(odgovor.Poruka);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            location.reload();
        }
    });

};

/**
 * Dohvati obavijesti.
 *
 * @param {object} element
 * @param {int} $broj_stranice
 * @param {string} $poredaj
 * @param {string} $redoslijed
 */
$_Obavijesti = function (element = '', $broj_stranice = 1, $poredaj = 'Obavijest', $redoslijed = 'asc') {

    let podatci = $('form[data-oznaka="obavijesti_lista"]').serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/obavijesti/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed,
        dataType: 'json',
        data: podatci,
        success: function (odgovor) {
            $('form[data-oznaka="obavijesti_lista"] > section table tbody').empty();
            let Obavijesti = odgovor.Obavijesti;
            $.each(Obavijesti, function (a, Obavijest) {
                if (Obavijest.Aktivan === "1") {Obavijest.Aktivan = '\
                    <label data-boja="boja" class="kontrolni_okvir">\
                        <input type="checkbox" disabled checked><span class="kontrolni_okvir"><span class="ukljuceno"></span></span>\
                    </label>\
                ';} else {Obavijest.Aktivan = '\
                    <label data-boja="boja" class="kontrolni_okvir">\
                        <input type="checkbox" disabled><span class="kontrolni_okvir"><span class="ukljuceno"></span></span>\
                    </label>\
                ';}
                $('form[data-oznaka="obavijesti_lista"] > section table tbody').append('\
                    <tr onclick="$_Obavijest(\''+ Obavijest.ID +'\')">\
                        <td class="uredi">'+ Obavijest.ID +'</td>\
                        <td class="uredi">'+ Obavijest.Obavijest +'</td>\
                        <td class="uredi"><img src="/slika/baner/'+ Obavijest.Obavijest +'" /></td>\
                        <td class="uredi">'+ Obavijest.Redoslijed +'</td>\
                    </tr>\
                ');
            });
            // zaglavlje
            let Zaglavlje = odgovor.Zaglavlje;
            $('form[data-oznaka="obavijesti_lista"] > section div.sadrzaj > table thead').empty().append(Zaglavlje);
            // navigacija
            let Navigacija = odgovor.Navigacija;
            $('form[data-oznaka="obavijesti_lista"] > section div.kontrole').empty().append('<ul class="navigacija">' + Navigacija.pocetak + '' + Navigacija.stranice + '' + Navigacija.kraj + '</ul>');
        },
        error: function () {
        }
    });

    return false;

};

/**
 * Uredi obavijest.
 *
 * @param {int} $id
 */
$_Obavijest = function ($id) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/obavijesti/uredi/' + $id,
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Obavijest: ' + $id);
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ObavijestSpremi(this, \'forma\');"><svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ObavijestIzbrisi(this,  \'forma\');"><svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg><span>Izbrisi</span></button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            $(function () {
                $('.tagovi').tagovi_input({
                    width: 'auto'
                });
                $(".input-select").chosen({
                    search_contains: true,
                    width: '100%'
                });
            });
        }
    });

    return false;

};

/**
 * Spremi obavijest.
 */
$_ObavijestSpremi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let obavijest_forma = $('form[data-oznaka="obavijest"]');

    let $id = obavijest_forma.data("sifra");

    let $podatci = obavijest_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/obavijesti/spremi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno spremljeno');
                dialog.sadrzaj('Postavke obavijesti su spremljene!');
                dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');

            } else {
                $(element).closest('form').find('table tr.poruka td').append(odgovor.Poruka);
            }
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            $_Obavijesti();
        }
    });

    return false;

};

/**
 * Izbriši obavijest.
 */
$_ObavijestIzbrisi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let obavijest_forma = $('form[data-oznaka="obavijest"]');

    let $id = obavijest_forma.data("sifra");

    $.ajax({
        type: 'POST',
        url: '/administrator/obavijesti/izbrisi/' + $id,
        dataType: 'json',
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno izbrisano');
                dialog.sadrzaj('Obavijest je izbrisana!');
                dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');

            } else {
                $(element).closest('form').find('table tr.poruka td').append(odgovor.Poruka);
            }
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.sadrzaj('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            $_Obavijesti();
        }
    });

    return false;

};