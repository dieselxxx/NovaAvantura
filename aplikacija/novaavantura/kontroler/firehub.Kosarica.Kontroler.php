<?php declare(strict_types = 1);

/**
 * Košarica
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2024 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\NovaAvantura\Kontroler;

use FireHub\Jezgra\Model\Model;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\NovaAvantura\Model\Kosarica_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Artikli_Model;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Domena;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Validacija;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Email;
use Throwable;

/**
 * ### Košarica
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Kosarica_Kontroler extends Master_Kontroler {

    protected Model $kosarica;

    protected array $artikli;
    private array $kosarica_artikli = [];


    private float $total_cijena = 0;
    private float $total_kolicina = 0;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->kosarica = $this->model(Kosarica_Model::class);
        $this->artikli = $this->model(Artikli_Model::class)->artikli('sve kategorije', 0, PHP_INT_MAX, 'svi artikli', 0, PHP_INT_MAX, 'sve', 'sve', 'cijena', 'asc');

        // kosarica artikli
        $this->kosarica_artikli = $this->kosarica->artikliID();
        if (!empty($this->kosarica_artikli)) {

            foreach ($this->kosarica_artikli as $kosarica_artikal) {

                $artikal = array_filter($this->artikli, function ($value) use ($kosarica_artikal) {
                    return $value['ID'] === $kosarica_artikal['id'];
                });
                $artikal = $artikal[array_key_first($artikal)];

                $this->total_cijena += $artikal['CijenaFinal'] * $kosarica_artikal['kolicina'];

                $this->total_kolicina += $kosarica_artikal['kolicina'];

            }

            // dostava
            if ($this->total_cijena < Domena::dostavaLimit()) {

                $this->kosarica_artikli[] = [
                    'id' => 0,
                    'velicina' => 0,
                    'velicinaNaziv' => 'nema',
                    'kolicina' => 1
                ];

                $this->artikli[] = [
                    'ID' => 0,
                    'Link' => '',
                    'Slika' => '',
                    'Naziv' => 'Dostava',
                    'CijenaHTML' => number_format(Domena::dostavaIznos(), 2, ',', '.').' '.Domena::valuta(),
                    'CijenaFinalHTML' => number_format(Domena::dostavaIznos(), 2, ',', '.')
                ];

                $this->total_cijena += Domena::dostavaIznos();

            }

        }

        parent::__construct();

    }

    /**
     * ### index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        // kosarica artikli
        $artikli_html = '';
        if (!empty($this->kosarica_artikli)) {

            foreach ($this->kosarica_artikli as $kosarica_artikal) {

                $artikal = array_filter($this->artikli, function ($value) use ($kosarica_artikal) {
                    return $value['ID'] === $kosarica_artikal['id'];
                });
                $artikal = $artikal[array_key_first($artikal)];

                if ($kosarica_artikal['id'] !== 0) {

                    $artikli_html .= <<<Artikal

                        <form class="artikal" method="post" enctype="multipart/form-data" action="">
                            <input type="hidden" name="ID" value="{$artikal['ID']}" />
                            <input type="hidden" name="velicina" value="{$kosarica_artikal['velicina']}">
                            <a class="slika" href="/artikl/{$artikal['Link']}">
                                <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                            </a>
                            <a class="naziv" href="/artikl/{$artikal['Link']}">
                                {$artikal['Naziv']}<br>
                                <span class="velicina">Veličina: {$kosarica_artikal['velicinaNaziv']}</span>
                            </a>
                            <span class="cijena"><span>{$kosarica_artikal['kolicina']} x</span> {$artikal['CijenaHTML']}</span>
                            <span class="kosarica">
                                <label class="input">
                                    <input type="number" name="vrijednost" data-pakiranje="1" data-maxpakiranje="1000" value="{$kosarica_artikal['kolicina']}" min="1" max="100" step="1" autocomplete="off" pattern="0-9">
                                </label>
                                <button type="button" class="gumb minus" onclick="ArtikalPlusMinus(this, 'minus');">-</button>
                                <button type="button" class="gumb plus" onclick="ArtikalPlusMinus(this, 'plus');">+</button>
                                <button type="submit" class="gumb ikona uredi" name="kosarica_izmijeni">
                                    <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#uredi"></use></svg>
                                    <span>Izmijeni</span>
                                </button>
                            </span>
                            <button class="izbrisi" type="submit" class="gumb ikona" name="kosarica_izbrisi">
                                <span>Ukloni</span>
                            </button>
                        </form>

                    Artikal;

                } else {

                    $artikli_html .= <<<Artikal

                        <form class="artikal" method="post" enctype="multipart/form-data" action="">
                            <a class="slika">
                                <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#dostava"></use></svg>
                            </a>
                            <a class="naziv"> {$artikal['Naziv']}<br></a>
                            <span class="cijena"><span>{$kosarica_artikal['kolicina']} x</span> {$artikal['CijenaHTML']}</span>
                        </form>

                    Artikal;

                }

            }

        }

        return sadrzaj()->datoteka('kosarica.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'Košarica',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Košarica',
            'artikli' => empty($artikli_html) ? '<h2>Vaša košarica je prazna!</h2>' : $artikli_html,
            'total_cijena' => number_format($this->total_cijena, 2, ',', '.').' '.Domena::valuta(),
            'total_kolicina' => (string)$this->total_kolicina
        ]));

    }

    /**
     * ### Narudžba
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function narudzba ():Sadrzaj {

        if ($this->kosarica->brojArtikala() === 0) header("Location: /");

        return sadrzaj()->datoteka('narudzba.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'Narudžba',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Narudžba',
            'domena_oibpdv' => Domena::OIBPDV(),
            'domena_valuta' => Domena::valuta(),
            'forma_ime' => $_POST['ime'] ?? '',
            'forma_email' => $_POST['email'] ?? '',
            'forma_telefon' => $_POST['telefon'] ?? '',
            'forma_grad' => $_POST['grad'] ?? '',
            'forma_adresa' => $_POST['adresa'] ?? '',
            'forma_zip' => $_POST['zip'] ?? '',
            'forma_tvrtka' => $_POST['tvrtka'] ?? '',
            'forma_oib' => $_POST['oib'] ?? '',
            'forma_tvrtka_adresa' => $_POST['tvrtkaadresa'] ?? '',
            'forma_placanje' => $_POST['placanje'] ?? '',
            'forma_napomena' => $_POST['napomena'] ?? '',
        ]));

    }

    /**
     * ### Ispravna narudžba
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function ispravno ():Sadrzaj {

        session_start();

        $this->kosarica->unistiSesiju();

        return sadrzaj()->datoteka('narudzba_ispravno.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'Narudžba',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Narudžba'
        ]));

    }

    /**
     * ### Naruči košarice
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    protected function naruci ():void {

        $ime = $_POST['ime'];
        $email = $_POST['email'];
        $telefon = $_POST['telefon'];
        $r1 = $_POST['r1'] ?? false;
        $grad = $_POST['grad'];
        $adresa = $_POST['adresa'];
        $zip = $_POST['zip'];
        $tvrtka = $_POST['tvrtka'];
        $oib = $_POST['oib'];
        $tvrtkaadresa = $_POST['tvrtkaadresa'];
        $placanje = $_POST['placanje'];
        $napomena = $_POST['napomena'];

        $ime = Validacija::String(_('Vaše ime'), $ime, 2, 100);
        $telefon = Validacija::String(_('Vaš broj telefona'), $telefon, 5, 20);
        if($tvrtka <> '') {$tvrtka = Validacija::String(_('Vaša tvrtka'), $tvrtka, 4, 100);}
        if($oib <> '') {$oib = Validacija::String(_('Vaš OIB \ PDV \ ID tvrtke'), $oib, 1, 20);}
        if($tvrtkaadresa <> '') {$tvrtkaadresa = Validacija::String(_('Vaša adresa tvrtka'), $tvrtkaadresa, 4, 100);}
        $placanje = Validacija::Broj(_('Plaćanje'), $placanje, 1, 1);
        $napomena = Validacija::String("Vaša napomena", $napomena, 0, 1000);

        // kosarica artikli
        /*$artikli_html = '';
        if (!empty($this->kosarica_artikli)) {

            foreach ($this->kosarica_artikli as $kosarica_artikal) {

                $artikal = array_filter($this->artikli, function ($value) use ($kosarica_artikal) {
                    return $value['ID'] === $kosarica_artikal['id'];
                });
                $artikal = $artikal[array_key_first($artikal)];

                if ($kosarica_artikal['id'] !== 0) {

                    $iznos = number_format($artikal['CijenaFinal'] * $kosarica_artikal['kolicina'], 2, ',', '.');

                    $artikli_html .= <<<Artikal

                        <tr>
                            <td align='center'>{$kosarica_artikal['barkod']}</td>
                            <td align='center'>{$kosarica_artikal['velicina']}</td>
                            <td align='center' style='text-align: center;'>{$kosarica_artikal['velicinaNaziv']}</td>
                            <td align='left'>{$artikal['Naziv']}</td>
                            <td align='center' style='text-align: center;'>{$kosarica_artikal['kolicina']} kom</td>
                            <td align='right' style='text-align: right;'>{$artikal['CijenaFinalHTML']}</td>
                            <td align='right' style='text-align: right;'>{$iznos}</td>

                        </tr>

                    Artikal;

                } else {

                    $artikli_html .= <<<Artikal

                        <tr>
                            <td align='center'></td>
                            <td align='center' style='text-align: center;'></td>
                            <td align='left'>{$artikal['Naziv']}</td>
                            <td align='center' style='text-align: center;'>{$kosarica_artikal['kolicina']} kom</td>
                            <td align='right' style='text-align: right;'>{$artikal['CijenaFinalHTML']}</td>
                        </tr>

                    Artikal;

                }

            }

        }

        // pošalji email
        $email_slanje_tvrtka = new Email('narudzba.html');
        $email_slanje_tvrtka->Naslov('Vaša narudžba je zaprimljena');
        $email_slanje_tvrtka->Adresa(array(
            array("adresa" => 'danijel.galic@outlook.com', "ime" => 'Danijel Galic'),
            array("adresa" => $email, "ime" => $ime),
            //array("adresa" => 'danijel.galic@outlook.com', "ime" => 'Danijel Galic')
        ));
        $email_slanje_tvrtka->PredlozakKomponente(array(
            "ime" => $ime,
            "email" => $email,
            "telefon" => $telefon,
            "r1" => $r1 ? 'Potreban R1 račun: <b>da</b>' : '',
            "grad" => $grad,
            "adresa" => $adresa,
            "zip" => $zip,
            "tvrtka" => $tvrtka ? 'Tvrtka: <b>'.$tvrtka.'</b>': '',
            "oib" => $oib ? Domena::OIBPDV().': <b>'.$oib.'</b>' : '',
            "tvrtkaadresa" => $tvrtkaadresa ? 'Adresa tvrtke: <b>'.$tvrtkaadresa.'</b>' : '',
            "placanje" => $placanje == 1 ? 'Plaćanje pouzećem - gotovina' : 'Virman',
            "napomena" => $napomena,
            "datum" =>  date("d.m.Y"),
            "artikli" => $artikli_html,
            "total_kolicina" => $this->total_kolicina . ' kom',
            "total_cijena" => number_format($this->total_cijena, 2, ',', '.').' '.Domena::valuta(),
            "tvrtka_adresa" => Domena::adresa(),
            "tvrtka_telefon" => Domena::telefon(),
            "tvrtka_email" => Domena::email(),
            "valuta" => Domena::valuta()
        ));*/
        //$email_slanje_tvrtka->Posalji();

    }

}