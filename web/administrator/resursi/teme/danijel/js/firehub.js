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

        console.log(this);

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
            //location.reload();
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
 * Dohvati artikle.
 *
 * @param {object} element
 * @param {int} $broj_stranice
 * @param {string} $poredaj
 * @param {string} $redoslijed
 * @param {string} $kategorija
 */
$_Artikli = function (element = '', $broj_stranice = 1, $poredaj = 'Naziv', $redoslijed = 'asc', $kategorija = '') {

    let podatci = $('form[data-oznaka="artikli_lista"]').serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed + '/' + $kategorija,
        dataType: 'json',
        data: podatci,
        success: function (odgovor) {
            $('form[data-oznaka="artikli_lista"] > section table tbody').empty();
            let Artikli = odgovor.Artikli;
            $.each(Artikli, function (a, Artikal) {
                if (Artikal.Aktivan === "1") {Artikal.Aktivan = '\
                <label data-boja="boja" class="kontrolni_okvir">\
                    <input type="checkbox" disabled checked><span class="kontrolni_okvir"><span class="ukljuceno"></span></span>\
                </label>\
            ';} else {Artikal.Aktivan = '\
                <label data-boja="boja" class="kontrolni_okvir">\
                    <input type="checkbox" disabled><span class="kontrolni_okvir"><span class="ukljuceno"></span></span>\
                </label>\
            ';}
                $('form[data-oznaka="artikli_lista"] > section table tbody').append('\
                <tr>\
                    <td onclick="$_Artikl(\''+ Artikal.ID +'\')" class="uredi">'+ Artikal.ID +'</td>\
                    <td onclick="$_Artikl(\''+ Artikal.ID +'\')" class="uredi">'+ Artikal.Naziv +'</td>\
                    <td><a onclick="$_ArtiklSifre(\''+ Artikal.ID +'\')">Uredi šifre</a></td>\
                    <td><a onclick="$_ArtiklZaliha(\''+ Artikal.ID +'\')">Uredi zalihu</a></td>\
                    <td onclick="$_Artikl(\''+ Artikal.ID +'\')" class="uredi">'+ Artikal.Aktivan +'</td>\
                </tr>\
            ');
            });
            // zaglavlje
            let Zaglavlje = odgovor.Zaglavlje;
            $('form[data-oznaka="artikli_lista"] > section div.sadrzaj > table thead').empty().append(Zaglavlje);
            // navigacija
            let Navigacija = odgovor.Navigacija;
            $('form[data-oznaka="artikli_lista"] > section div.kontrole').empty().append('<ul class="navigacija">' + Navigacija.pocetak + '' + Navigacija.stranice + '' + Navigacija.kraj + '</ul>');
        },
        error: function () {
        }
    });

    return false;

};

/**
 * Uredi artikl.
 *
 * @param {int} $id
 */
$_Artikl = function ($id) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/artikli/uredi/' + $id,
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Artikl: ' + $id);
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ArtiklSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
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
 * Novi artikl.
 */
$_NoviArtikl = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/artikli/novi/',
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Novi artikl');
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ArtiklSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
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
 * Spremi artikl.
 */
$_ArtiklSpremi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let artikl_forma = $('form[data-oznaka="artikl"]');

    let $id = artikl_forma.data("sifra");

    let $podatci = artikl_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/spremi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno spremljeno');
                dialog.sadrzaj('Postavke artikla su spremljene!');
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
            $_Artikli();
        }
    });

    return false;

};

/**
 * Uredi šifre artikla.
 *
 * @param {int} $id
 */
$_ArtiklSifre = function ($id) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/artikli/artiklsifre/' + $id,
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Artikl: ' + $id);
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ArtiklSifreNova(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#artikl"></use></svg><span>Nova šifra</span></button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ArtiklSifreSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
        }
    });

    return false;

};

/**
 * Nova šifre artikla.
 */
$_ArtiklSifreNova = function (element) {

    $(element).closest('form').find('table tbody').append('\
        <tr>\
            <td>\
                <label data-boja="boja" class="unos">\
                    <input type="text" name="zaliha[]" value="" maxlength="50" autocomplete="off">\
                    <span class="naslov">\
                        <span>Šifra</span>\
                    </span>\
                    <span class="granica"></span>\
                    <svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#cijena"></use></svg>\
                    <span class="upozorenje"></span>\
                </label>\
            </td>\
            <td>\
                <label data-boja="boja" class="unos">\
                    <input type="text" name="velicina[]" value="" maxlength="50" autocomplete="off">\
                    <span class="naslov">\
                        <span>Veličina</span>\
                    </span>\
                    <span class="granica"></span>\
                    <svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#cijena"></use></svg>\
                    <span class="upozorenje"></span>\
                </label>\
            </td>\
            <td>\
                <label data-boja="boja" class="unos">\
                    <input type="text" name="barkod[]" value="" maxlength="13" autocomplete="off">\
                    <span class="naslov">\
                        <span>Barkod</span>\
                    </span>\
                    <span class="granica"></span>\
                    <svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#barkod"></use></svg>\
                    <span class="upozorenje"></span>\
                </label>\
            </td>\
            <td>\
                <button type="button" class="ikona" onclick="$_ArtiklSifreIzbrisi(this, 0, 0)"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg><span></span></button>\
            </td>\
        </tr>\
        ');

    return false;

};

/**
 * Izbriši šifru artikla.
 *
 * @param {object} element
 * @package {int} $id
 */
$_ArtiklSifreIzbrisi = function (element, $id, $artikl) {

    if ($id == 0) {

        $(element).closest('tr').remove();

    } else {

        $.ajax({
            type: 'POST',
            url: '/administrator/artikli/artiklsifreizbrisi/' + $id,
            dataType: 'json',
            beforeSend: function () {
                $(element).closest('form').find('table tr.poruka td').empty();
            },
            success: function (odgovor) {
                if (odgovor.Validacija === "da") {
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
                $_ArtiklSifre($artikl);
            }
        });

    }

    return false;

};

/**
 * Spremi šifre artikla.
 */
$_ArtiklSifreSpremi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let artikl_forma = $('form[data-oznaka="artiklsifre"]');

    let $id = artikl_forma.data("sifra");

    let $podatci = artikl_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/artiklsifrespremi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno spremljeno');
                dialog.sadrzaj('Postavke artikla su spremljene!');
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
        }
    });

    return false;

};

/**
 * Uredi zalihu artikla.
 *
 * @param {int} $id
 */
$_ArtiklZaliha = function ($id) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/artikli/uredizalihu/' + $id,
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Artikl: ' + $id);
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ArtiklZalihaSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
        }
    });

    return false;

};

/**
 * Spremi zalihu artikla.
 */
$_ArtiklZalihaSpremi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let artikl_forma = $('form[data-oznaka="artiklzaliha"]');

    let $id = artikl_forma.data("sifra");

    let $podatci = artikl_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/zalihaspremi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno spremljeno');
                dialog.sadrzaj('Postavke artikla su spremljene!');
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
        }
    });

    return false;

};

/**
 * Izbriši cijenu artikla.
 */
$_ArtiklIzbrisiCijenu = function ($slika) {

    let artikl_forma = $('form[data-oznaka="artikl"]');

    let $id = artikl_forma.data("sifra");

    let $podatci = artikl_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/izbrisicijenu/' + $slika,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            //
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

            } else {
                //$(element).closest('form').find('table tr.poruka td').append(odgovor.Poruka);
            }
        },
        error: function () {
        },
        complete: function (odgovor) {
            $_Artikl($id);
        }
    });

    return false;

};

/**
 * Spremi sliku artikla.
 */
$(function() {

    $("body").on('submit', 'form[data-oznaka="artikl"]', function() {

        let oznaka = $(this).data("oznaka");

        $_ArtiklSpremiSliku('form[data-oznaka="' + oznaka + '"]');

        return false;

    }).on('change','form[data-oznaka="artikl"] input[type="file"]', function() {

        let oznaka = $(this).closest('form').data("oznaka");

        $('form[data-oznaka="' + oznaka + '"]').submit();

        return false;

    });

});
$_ArtiklSpremiSliku = function ($url) {

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
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function(odgovor) {
            $_Artikl($id);
        }
    });

    return false;

};

/**
 * Izbriši sliku artikla.
 */
$_ArtiklIzbrisiSliku = function ($slika) {

    let artikl_forma = $('form[data-oznaka="artikl"]');

    let $id = artikl_forma.data("sifra");

    let $podatci = artikl_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/artikli/izbrisisliku/' + $slika,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            //
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

            } else {
                //$(element).closest('form').find('table tr.poruka td').append(odgovor.Poruka);
            }
        },
        error: function () {
        },
        complete: function (odgovor) {
            $_Artikl($id);
        }
    });

    return false;

};

/**
 * Spremi sliku obavijesti.
 */
$(function() {

    $("body").on('submit', 'form[data-oznaka="obavijest"]', function() {

        console.log(this);

        let oznaka = $(this).data("oznaka");

        $_ObavijestSpremiSliku('form[data-oznaka="' + oznaka + '"]');

        return false;

    }).on('change','form[data-oznaka="obavijest"] input[type="file"]', function() {

        let oznaka = $(this).closest('form').data("oznaka");

        $('form[data-oznaka="' + oznaka + '"]').submit();

        return false;

    });

});
$_ObavijestSpremiSliku = function ($url) {

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
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function(odgovor) {
            $_Obavijest($id);
        }
    });

    return false;

};

/**
 * Dohvati kategorije.
 *
 * @param {object} element
 * @param {int} $broj_stranice
 * @param {string} $poredaj
 * @param {string} $redoslijed
 */
$_Kategorije = function (element = '', $broj_stranice = 1, $poredaj = 'Kategorija', $redoslijed = 'asc') {

    let podatci = $('form[data-oznaka="kategorije_lista"]').serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/kategorije/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed,
        dataType: 'json',
        data: podatci,
        success: function (odgovor) {
            $('form[data-oznaka="kategorije_lista"] > section table tbody').empty();
            let Kategorije = odgovor.Kategorije;
            $.each(Kategorije, function (a, Kategorija) {
                $('form[data-oznaka="kategorije_lista"] > section table tbody').append('\
                    <tr onclick="$_Kategorija(\''+ Kategorija.ID +'\')">\
                        <td class="uredi">'+ Kategorija.ID +'</td>\
                        <td class="uredi">'+ Kategorija.Kategorija +'</td>\
                        <td class="uredi">'+ Kategorija.Roditelj +'</td>\
                    </tr>\
                ');
            });
            // zaglavlje
            let Zaglavlje = odgovor.Zaglavlje;
            $('form[data-oznaka="kategorije_lista"] > section div.sadrzaj > table thead').empty().append(Zaglavlje);
            // navigacija
            let Navigacija = odgovor.Navigacija;
            $('form[data-oznaka="kategorije_lista"] > section div.kontrole').empty().append('<ul class="navigacija">' + Navigacija.pocetak + '' + Navigacija.stranice + '' + Navigacija.kraj + '</ul>');
        },
        error: function () {
        }
    });

    return false;

};

/**
 * Uredi kategoriju.
 *
 * @param {int} $id
 */
$_Kategorija = function ($id) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/kategorije/uredi/' + $id,
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Kategorija: ' + $id);
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_KategorijaIzbrisi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg><span>Izbriši</span></button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_KategorijaSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
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
 * Nova kategorija.
 */
$_KategorijaNova = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/kategorije/nova/',
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Nova kategorija');
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_KategorijaSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
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
            $_Kategorije();
        }
    });

    return false;

};

/**
 * Spremi kategoriju.
 */
$_KategorijaSpremi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let kategorija_forma = $('form[data-oznaka="kategorija"]');

    let $id = kategorija_forma.data("sifra");

    let $podatci = kategorija_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/kategorije/spremi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno spremljeno');
                dialog.sadrzaj('Postavke kategorije su spremljene!');
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
            $_Kategorije();
        }
    });

    return false;

};

/**
 * Izbrisi kategoriju.
 */
$_KategorijaIzbrisi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let kategorija_forma = $('form[data-oznaka="kategorija"]');

    let $id = kategorija_forma.data("sifra");

    let $podatci = kategorija_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/kategorije/izbrisi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno izbrisano');
                dialog.sadrzaj('Kategorija je izbrisana!');
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
            $_Kategorije();
        }
    });

    return false;

};

/**
 * Spremi sliku kategorije.
 */
$(function() {

    $("body").on('submit', 'form[data-oznaka="kategorija"]', function() {

        let oznaka = $(this).data("oznaka");

        $_KategorijaSpremiSliku('form[data-oznaka="' + oznaka + '"]');

        return false;

    }).on('change','form[data-oznaka="kategorija"] input[type="file"]', function() {

        let oznaka = $(this).closest('form').data("oznaka");

        $('form[data-oznaka="' + oznaka + '"]').submit();

        return false;

    });

});
$_KategorijaSpremiSliku = function ($url) {

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
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function(odgovor) {
            $_Kategorija($id);
        }
    });

    return false;

};

/**
 * Dohvati brandve.
 *
 * @param {object} element
 * @param {int} $broj_stranice
 * @param {string} $poredaj
 * @param {string} $redoslijed
 */
$_Brandovi = function (element = '', $broj_stranice = 1, $poredaj = 'Brand', $redoslijed = 'asc') {

    let podatci = $('form[data-oznaka="brandovi_lista"]').serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/brandovi/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed,
        dataType: 'json',
        data: podatci,
        success: function (odgovor) {
            $('form[data-oznaka="brandovi_lista"] > section table tbody').empty();
            let Brandovi = odgovor.Brandovi;
            $.each(Brandovi, function (a, Brandovi) {
                $('form[data-oznaka="brandovi_lista"] > section table tbody').append('\
                    <tr onclick="$_Brand(\''+ Brandovi.ID +'\')">\
                        <td class="uredi">'+ Brandovi.ID +'</td>\
                        <td class="uredi">'+ Brandovi.Brand +'</td>\
                    </tr>\
                ');
            });
            // zaglavlje
            let Zaglavlje = odgovor.Zaglavlje;
            $('form[data-oznaka="brandovi_lista"] > section div.sadrzaj > table thead').empty().append(Zaglavlje);
            // navigacija
            let Navigacija = odgovor.Navigacija;
            $('form[data-oznaka="brandovi_lista"] > section div.kontrole').empty().append('<ul class="navigacija">' + Navigacija.pocetak + '' + Navigacija.stranice + '' + Navigacija.kraj + '</ul>');
        },
        error: function () {
        }
    });

    return false;

};

/**
 * Uredi brand.
 *
 * @param {int} $id
 */
$_Brand = function ($id) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/brandovi/uredi/' + $id,
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Brand: ' + $id);
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_BrandIzbrisi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg><span>Izbriši</span></button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_BrandSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {}
    });

    return false;

};

/**
 * Novi brand.
 */
$_BrandNovi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/brandovi/nova/',
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Novi brand');
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_BrandSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            $_Brandovi();
        }
    });

    return false;

};

/**
 * Spremi brand.
 */
$_BrandSpremi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let brand_forma = $('form[data-oznaka="brand"]');

    let $id = brand_forma.data("sifra");

    let $podatci = brand_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/brandovi/spremi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno spremljeno');
                dialog.sadrzaj('Postavke branda su spremljene!');
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
            $_Brandovi();
        }
    });

    return false;

};

/**
 * Izbrisi brand.
 */
$_BrandIzbrisi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let brand_forma = $('form[data-oznaka="brand"]');

    let $id = brand_forma.data("sifra");

    let $podatci = brand_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/brandovi/izbrisi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno izbrisano');
                dialog.sadrzaj('Brand je izbrisan!');
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
            $_Brandovi();
        }
    });

    return false;

};

/**
 * Spremi sliku branda.
 */
$(function() {

    $("body").on('submit', 'form[data-oznaka="brand"]', function() {

        let oznaka = $(this).data("oznaka");

        $_BrandSpremiSliku('form[data-oznaka="' + oznaka + '"]');

        return false;

    }).on('change','form[data-oznaka="brand"] input[type="file"]', function() {

        let oznaka = $(this).closest('form').data("oznaka");

        $('form[data-oznaka="' + oznaka + '"]').submit();

        return false;

    });

});
$_BrandSpremiSliku = function ($url) {

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
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function(odgovor) {
            $_Brand($id);
        }
    });

    return false;

};

/**
 * Dohvati blogove.
 *
 * @param {object} element
 * @param {int} $broj_stranice
 * @param {string} $poredaj
 * @param {string} $redoslijed
 */
$_Blogovi = function (element = '', $broj_stranice = 1, $poredaj = 'Datum', $redoslijed = 'desc') {

    let podatci = $('form[data-oznaka="blogovi_lista"]').serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/blogovi/lista/' + $broj_stranice + '/' + $poredaj + '/' + $redoslijed,
        dataType: 'json',
        data: podatci,
        success: function (odgovor) {
            $('form[data-oznaka="blogovi_lista"] > section table tbody').empty();
            let Blogovi = odgovor.Blogovi;
            $.each(Blogovi, function (a, Blogovi) {
                $('form[data-oznaka="blogovi_lista"] > section table tbody').append('\
                    <tr onclick="$_Blog(\''+ Blogovi.ID +'\')">\
                        <td class="uredi">'+ Blogovi.ID +'</td>\
                        <td class="uredi">'+ Blogovi.Datum +'</td>\
                        <td class="uredi">'+ Blogovi.Naslov +'</td>\
                    </tr>\
                ');
            });
            // zaglavlje
            let Zaglavlje = odgovor.Zaglavlje;
            $('form[data-oznaka="blogovi_lista"] > section div.sadrzaj > table thead').empty().append(Zaglavlje);
            // navigacija
            let Navigacija = odgovor.Navigacija;
            $('form[data-oznaka="blogovi_lista"] > section div.kontrole').empty().append('<ul class="navigacija">' + Navigacija.pocetak + '' + Navigacija.stranice + '' + Navigacija.kraj + '</ul>');
        },
        error: function () {
        }
    });

    return false;

};

/**
 * Uredi blog.
 *
 * @param {int} $id
 */
$_Blog = function ($id) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/blogovi/uredi/' + $id,
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Blog: ' + $id);
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_BlogIzbrisi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg><span>Izbriši</span></button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_BlogSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {}
    });

    return false;

};

/**
 * Novi blog.
 */
$_BlogNovi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    $.ajax({
        type: 'GET',
        url: '/administrator/blogovi/novi/',
        dataType: 'html',
        context: this,
        beforeSend: function () {
            Dialog.dialogOtvori(true);
            dialog.sadrzaj(Loader_Krug);
        },
        success: function (odgovor) {
            Dialog.dialogOcisti();
            dialog.naslov('Novi blog');
            dialog.sadrzaj(odgovor);
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_BlogSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
        },
        error: function () {
            Dialog.dialogOcisti();
            dialog.naslov('Greška');
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function (odgovor) {
            $_Blogovi();
        }
    });

    return false;

};

/**
 * Spremi blog.
 */
$_BlogSpremi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let blog_forma = $('form[data-oznaka="blog"]');

    let $id = blog_forma.data("sifra");

    let $podatci = blog_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/blogovi/spremi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno spremljeno');
                dialog.sadrzaj('Postavke bloga su spremljene!');
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
            $_Blogovi();
        }
    });

    return false;

};

/**
 * Izbrisi blog.
 */
$_BlogIzbrisi = function (element) {

    // dialog prozor
    let dialog = new Dialog();

    let blog_forma = $('form[data-oznaka="blog"]');

    let $id = blog_forma.data("sifra");

    let $podatci = blog_forma.serializeArray();

    $.ajax({
        type: 'POST',
        url: '/administrator/blogovi/izbrisi/' + $id,
        dataType: 'json',
        data: $podatci,
        beforeSend: function () {
            $(element).closest('form').find('table tr.poruka td').empty();
        },
        success: function (odgovor) {
            if (odgovor.Validacija === "da") {

                Dialog.dialogOcisti();
                dialog.naslov('Uspješno izbrisano');
                dialog.sadrzaj('Blog je izbrisan!');
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
            $_Blogovi();
        }
    });

    return false;

};

/**
 * Spremi sliku bloga.
 */
$(function() {

    $("body").on('submit', 'form[data-oznaka="blog"]', function() {

        let oznaka = $(this).data("oznaka");

        $_BlogSpremiSliku('form[data-oznaka="' + oznaka + '"]');

        return false;

    }).on('change','form[data-oznaka="blog"] input[type="file"]', function() {

        let oznaka = $(this).closest('form').data("oznaka");

        $('form[data-oznaka="' + oznaka + '"]').submit();

        return false;

    });

});
$_BlogSpremiSliku = function ($url) {

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
            dialog.naslov('Dogodila se greška prilikom učitavanja podataka, molimo kontaktirajte administratora');
            dialog.kontrole('<button data-boja="boja" onclick="Dialog.dialogZatvori()">Zatvori</button>');
        },
        complete: function(odgovor) {
            $_Blog($id);
        }
    });

    return false;

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
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ObavijestSpremi(this, \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#spremi"></use></svg><span>Spremi</span></button>');
            dialog.kontrole('<button type="button" class="ikona" onclick="$_ObavijestIzbrisi(this,  \'forma\');"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg><span>Izbrisi</span></button>');
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