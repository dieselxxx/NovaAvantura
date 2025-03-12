<?php declare(strict_types = 1);

/**
 * Brand
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

use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Model\Model;
use FireHub\Aplikacija\NovaAvantura\Model\Artikli_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Favoriti_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Brandovi_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Kategorije_Model;

/**
 * ### Artikli
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Brand_Kontroler extends Master_Kontroler {

    protected Model $artikli;
    protected Model $favoriti;

    protected Model $brandovi;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->artikli = $this->model(Artikli_Model::class);

        $this->favoriti = $this->model(Favoriti_Model::class);

        $this->brandovi = $this->model(Brandovi_Model::class);

        $this->kategorije = $this->model(Kategorije_Model::class);

        parent::__construct();

    }

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (
        string $kontroler = '', string $brand = 'sve', string $kategorija = 'sve kategorije',
        float|string $cijena_od = 'sve', float|string $cijena_do = 'sve', int|string $velicina = 'sve',
        string $poredaj = 'cijena', string $poredaj_redoslijed = 'asc', int $stranica = 1
    ):Sadrzaj {

        $trenutni_brand = $this->brandovi->brandPoLinku($brand);
        $limit = 15;
        $favoriti = $this->favoriti->artikli();

        // cijena
        $cijena_od = is_float($cijena_od) ? $cijena_od : 0;
        $cijena_do = is_float($cijena_do) ? $cijena_do : 100000;

        // artikli model
        $artikli = $this->artikli->artikli(
            $kategorija, ($stranica - 1) * $limit,
            $limit, 'svi artikli',
            (int)$cijena_od, (int)$cijena_do, $velicina, $brand, $poredaj, $poredaj_redoslijed
        );

        // artikli
        $artikli_html = '';
        foreach ($artikli as $artikal) {

            $fav = in_array($artikal['ID'], $favoriti) ? ' fill="red"' : '';

            $brand_slika = $artikal['BrandSlika'] ? '<img src="/slika/brand/'.$artikal['BrandSlika'].'" />' : '';

            $artikli_html .= <<<Artikal
            
                <form class="artikal" method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="ID" value="{$artikal['ID']}" />
                    <a class="slika" href="/artikl/{$artikal['Link']}">
                        {$artikal['PopustHTML']}
                        {$artikal['NovoHTML']}
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    </a>
                    <span class="brand">
                        <button type="submit" class="gumb ikona" name="favorit_dodaj">
                            <svg$fav><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti"></use></svg>
                        </button>
                        {$brand_slika}
                        {$artikal['Brand']}
                     </span>
                    <a class="naziv" href="/artikl/{$artikal['Link']}">{$artikal['Naziv']}</a>
                    <span class="cijena">{$artikal['CijenaHTML']}</span>
                    <span class="cijena_30_dana">{$artikal['Cijena30DanaHTML']}</span>
                </form>

            Artikal;

        }

        // navigacija
        $navigacija = $this->artikli->ukupnoRedakaHTML(
            $kategorija, 'svi artikli',
            $cijena_od, $cijena_do, $velicina, $brand, $limit,
            '/brand/'.$brand.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina .'/'.$poredaj.'/'.$poredaj_redoslijed, $stranica
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
            <option value="/brand/'.$brand.'/'.$kategorija.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/cijena/asc/" '.$poredaj_izbornik_odabrano_3.'>Cijena manja prema većoj</option>
            <option value="/brand/'.$brand.'/'.$kategorija.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/cijena/desc/" '.$poredaj_izbornik_odabrano_4.'>Cijena veća prema manjoj</option>
            <option value="/brand/'.$brand.'/'.$kategorija.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/naziv/asc/" '.$poredaj_izbornik_odabrano_1.'>Naziv A-Z</option>
            <option value="/brand/'.$brand.'/'.$kategorija.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/naziv/desc/" '.$poredaj_izbornik_odabrano_2.'>Naziv Z-A</option>
            <option value="/brand/'.$brand.'/'.$kategorija.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/starost/asc/" '.$poredaj_izbornik_odabrano_5.'>Starost manja prema većoj</option>
            <option value="/brand/'.$brand.'/'.$kategorija.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/starost/desc/" '.$poredaj_izbornik_odabrano_6.'>Starost veća prema manjoj</option>
        ';

        // prikazujem
        $prikazujem = 'Prikazujem '.$this->artikli->ukupnoRedaka(
                $kategorija, 'svi artikli', $cijena_od, $cijena_do, $velicina, $brand
            ).' artikala';

        // velicine meni
        $velicine = $this->artikli->velicine($kategorija, 'svi artikli', $cijena_od, $cijena_do, $brand);
        $velicina_meni = '';
        foreach ($velicine as $velicina1) {
            $checked = mb_strtolower($velicina1['Velicina']) === (string)$velicina
                ? 'checked': '';
            $velicina_meni .= '
                <li>
                    <label class="kontrolni_okvir">
                        <span>'.$velicina1['Velicina'].'</span>
                        <input type="checkbox" '.$checked.' data-url="/brand/'.$brand.'/'.$kategorija.'/'.$cijena_od.'/'.$cijena_do.'/'.mb_strtolower($velicina1['Velicina']).'/'.$poredaj.'/'.$poredaj_redoslijed.'/">
                        <span class="checkmark"></span>
                    </label>
                </li>
            ';
        }
        $velicine_meni = "
        <ul class='panel'>
            <li><a href=\"/brand/$brand/$kategorija/$cijena_od/$cijena_do/sve/$poredaj/$poredaj_redoslijed/\">Reset</a></li>
            $velicina_meni
        </ul>
        ";

        $cijena = empty($cijena = array_column($artikli, 'Cijena')) ? [0] : $cijena;

        // podkategorije meni
        $podkategorije = $this->kategorije->kategorijaPrva();
        $podkategorije_meni = '';
        if ($podkategorije) {

            $podkategorije_meni .= '
            <section class="podkategorije">
                <h4 class="accordion">Podkategorije</h4>
                <ul class="panel">';

            foreach ($podkategorije as $podkategorija) {
                $podkategorije_meni .= '<li>
                    <a href="/brand/'.$brand.'/'.$podkategorija['Link'].'"><span>&gt</span> '.$podkategorija['Kategorija'].'</a>
                </li>';
            }

            $podkategorije_meni .= '
                </ul>
            </section>';

        }

        // izdvojeni artikli
        $artikli_izdvojeno_html = '';
        $artikli = $this->artikli->artikli(
            'izdvojeno', 0, 8, 'svi artikli', 0, PHP_INT_MAX, 'sve', 'sve', 'starost', 'desc'
        );
        foreach ($artikli as $artikal) {

            $artikli_izdvojeno_html .= <<<Artikal
            
                <li>
                    <a href="/artikl/{$artikal['Link']}">
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                        <ul>
                            <li><span class="brand">{$artikal['Brand']}</span></li>
                            <li><span class="naziv">{$artikal['Naziv']}</span></li>
                            <li><span class="cijena">{$artikal['CijenaHTML']}</span></li>
                        </ul>
                    </a>
                </li>

            Artikal;

        }

        return sadrzaj()->datoteka('brand.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => $trenutni_brand['Brand'],
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ '.$trenutni_brand['Brand'],
            'brand_naziv' => $trenutni_brand['Brand'],
            'brand_opis' => $trenutni_brand['Opis'] ?? '',
            'brand_slika' => $trenutni_brand['Slika'] ? '<img src="/slika/brand/'.$trenutni_brand['Slika'].'" />' : '',
            'artikli' => $artikli_html,
            'navigacija' => $navigacija_html,
            "poredaj_izbornik" => $poredaj_izbornik,
            "prikazujem" => $prikazujem,
            "velicine_meni" => $velicine_meni,
            "podkategorije" => $podkategorije_meni,
            "cijena_min" => number_format((int)min($cijena)),
            "cijena_max" => number_format((int)max($cijena)),
            'izdvojeno' => $artikli_izdvojeno_html
        ]));

    }

}