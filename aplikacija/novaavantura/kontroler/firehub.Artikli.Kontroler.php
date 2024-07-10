<?php declare(strict_types = 1);

/**
 * Artikli
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\NovaAvantura\Kontroler;

use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Model\Model;
use FireHub\Aplikacija\NovaAvantura\Model\Kategorije_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Artikli_Model;

/**
 * ### Artikli
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Artikli_Kontroler extends Master_Kontroler {

    protected Model $kategorije;
    protected Model $artikli;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->kategorije = $this->model(Kategorije_Model::class);

        $this->artikli = $this->model(Artikli_Model::class);

        parent::__construct();

    }

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (string $kontroler = '', string $kategorija = 'sve', int|string $trazi = 'svi artikli', string $poredaj = 'cijena', string $poredaj_redoslijed = 'asc', int $stranica = 1):Sadrzaj {

        $trenutna_kategorija = $this->kategorije->kategorijaPoLinku($kategorija);
        $limit = 15;

        // artikli
        $artikli = $this->model(Artikli_Model::class)->artikli(
            $trenutna_kategorija['Link'], ($stranica - 1) * $limit,
            $limit, $trazi, $poredaj, $poredaj_redoslijed
        );
        $artikli_html = '';

        foreach ($artikli as $artikal) {

            $artikli_html .= <<<Artikal
            
                <form class="artikal" method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="ID" value="{$artikal['ID']}" />
                    <a class="slika" href="/artikl/{$artikal['Link']}">
                        {$artikal['PopustHTML']}
                        {$artikal['NovoHTML']}
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    </a>
                    <span class="brand">{$artikal['Brand']}</span>
                    <a class="naziv" href="/artikl/{$artikal['Link']}">{$artikal['Naziv']}</a>
                    <span class="cijena">{$artikal['CijenaHTML']}</span>
                    <span class="cijena_30_dana">{$artikal['Cijena30DanaHTML']}</span>
                </form>

            Artikal;

        }

        // navigacija
        $navigacija = $this->model(Artikli_Model::class)->ukupnoRedakaHTML(
            $trenutna_kategorija['Link'], $trazi, $limit,
            '/artikli/'.$trenutna_kategorija['Link'].'/'.$trazi .'/'.$poredaj.'/'.$poredaj_redoslijed, $stranica
        );
        $navigacija_html = implode('', $navigacija);

        // poredaj izbornik
        if ($poredaj === 'naziv' && $poredaj_redoslijed == 'asc') {$poredaj_izbornik_odabrano_1 = 'selected';} else {$poredaj_izbornik_odabrano_1 = '';}
        if ($poredaj === 'naziv' && $poredaj_redoslijed == 'desc') {$poredaj_izbornik_odabrano_2 = 'selected';} else {$poredaj_izbornik_odabrano_2 = '';}
        if ($poredaj === 'cijena' && $poredaj_redoslijed == 'asc') {$poredaj_izbornik_odabrano_3 = 'selected';} else {$poredaj_izbornik_odabrano_3 = '';}
        if ($poredaj === 'cijena' && $poredaj_redoslijed == 'desc') {$poredaj_izbornik_odabrano_4 = 'selected';} else {$poredaj_izbornik_odabrano_4 = '';}
        if ($poredaj === 'starost' && $poredaj_redoslijed == 'asc') {$poredaj_izbornik_odabrano_5 = 'selected';} else {$poredaj_izbornik_odabrano_5 = '';}
        if ($poredaj === 'starost' && $poredaj_redoslijed == 'desc') {$poredaj_izbornik_odabrano_6 = 'selected';} else {$poredaj_izbornik_odabrano_6 = '';}
        $poredaj_izbornik = '
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/cijena/asc/" '.$poredaj_izbornik_odabrano_3.'>Cijena manja prema većoj</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/cijena/desc/" '.$poredaj_izbornik_odabrano_4.'>Cijena veća prema manjoj</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/naziv/asc/" '.$poredaj_izbornik_odabrano_1.'>Naziv A-Z</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/naziv/desc/" '.$poredaj_izbornik_odabrano_2.'>Naziv Z-A</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/starost/asc/" '.$poredaj_izbornik_odabrano_5.'>Starost manja prema većoj</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/starost/desc/" '.$poredaj_izbornik_odabrano_6.'>Starost veća prema manjoj</option>
        ';

        // prikazujem
        $prikazujem = 'Prikazujem';

        return sadrzaj()->datoteka('artikli.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => $trenutna_kategorija['Kategorija'],
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Artikli \\ '.$trenutna_kategorija['Kategorija'],
            'kategorija_naziv' => $trenutna_kategorija['Kategorija'],
            'kategorija_opis' => $trenutna_kategorija['Opis'],
            'artikli' => $artikli_html,
            'navigacija' => $navigacija_html,
            "poredaj_izbornik" => $poredaj_izbornik,
            "prikazujem" => $prikazujem
        ]));

    }

}