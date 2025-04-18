<?php declare(strict_types = 1);

/**
 * Artikli
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
use FireHub\Aplikacija\NovaAvantura\Model\Kategorije_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Artikli_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Favoriti_Model;

/**
 * ### Artikli
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Artikli_Kontroler extends Master_Kontroler {

    protected Model $kategorije;
    protected Model $artikli;
    protected Model $favoriti;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->kategorije = $this->model(Kategorije_Model::class);

        $this->artikli = $this->model(Artikli_Model::class);

        $this->favoriti = $this->model(Favoriti_Model::class);

        parent::__construct();

    }

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (
        string $kontroler = '', string $kategorija = 'sve kategorije', int|string $trazi = 'svi artikli',
        float|string $cijena_od = 'sve', float|string $cijena_do = 'sve', int|string $velicina = 'sve', string $brand = 'sve',
        string $poredaj = 'cijena', string $poredaj_redoslijed = 'asc', int $stranica = 1
    ):Sadrzaj {

        $trenutna_kategorija = $this->kategorije->kategorijaPoLinku($kategorija);
        $limit = 15;
        $favoriti = $this->favoriti->artikli();

        // roditelji
        $roditelji = $this->kategorije->kategorijeRoditelji((int)$trenutna_kategorija['ID']);
        array_pop($roditelji);
        $roditelji_html = '';
        foreach ($roditelji as $roditelj) {

            $roditelji_html .= '<a href="/artikli/'.$roditelj['Link'].'">'.$roditelj['Kategorija'].'</a> \\ ';

        }

        // cijena
        $cijena_od = is_float($cijena_od) ? $cijena_od : 0;
        $cijena_do = is_float($cijena_do) ? $cijena_do : 100000;

        // artikli model
        $artikli = $this->artikli->artikli(
            $trenutna_kategorija['Link'], ($stranica - 1) * $limit,
            $limit, $trazi,
            (int)$cijena_od, (int)$cijena_do, $velicina, $brand, $poredaj, $poredaj_redoslijed
        );

        // artikli
        $artikli_html = '';
        foreach ($artikli as $artikal) {

            $fav = in_array($artikal['ID'], $favoriti)
                ? '<svg fill="red"><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti_puno"></use></svg>'
                : '<svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti"></use></svg>';

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
                            $fav
                        </button>
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
            $trenutna_kategorija['Link'], $trazi,
            $cijena_od, $cijena_do, $velicina, $brand, $limit,
            '/artikli/'.$trenutna_kategorija['Link'].'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/'.$brand .'/'.$poredaj.'/' .$poredaj_redoslijed, $stranica
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
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/'.$brand.'/cijena/asc/" '.$poredaj_izbornik_odabrano_3.'>Cijena manja prema većoj</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/'.$brand.'/cijena/desc/" '.$poredaj_izbornik_odabrano_4.'>Cijena veća prema manjoj</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/'.$brand.'/naziv/asc/" '.$poredaj_izbornik_odabrano_1.'>Naziv A-Z</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/'.$brand.'/naziv/desc/" '.$poredaj_izbornik_odabrano_2.'>Naziv Z-A</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/'.$brand.'/starost/asc/" '.$poredaj_izbornik_odabrano_5.'>Starost manja prema većoj</option>
            <option value="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/'.$brand.'/starost/desc/" '.$poredaj_izbornik_odabrano_6.'>Starost veća prema manjoj</option>
        ';

        // prikazujem
        $prikazujem = 'Prikazujem '.$this->artikli->ukupnoRedaka(
            $trenutna_kategorija['Link'], $trazi, $cijena_od, $cijena_do, $velicina, $brand
            ).' artikala';

        // podkategorije meni
        $podkategorije = $this->kategorije->kategorijeDjeca((int)$trenutna_kategorija['ID']);
        $podkategorije_meni = '';
        if ($podkategorije) {

            $podkategorije_meni .= '
            <section class="podkategorije">
                <h4 class="accordion">Podkategorije</h4>
                <ul class="panel">';

            foreach ($podkategorije as $podkategorija) {
                $podkategorije_meni .= '<li>
                    <a href="/artikli/'.$podkategorija['Link'].'"><span>&gt</span> '.$podkategorija['Kategorija'].'</a>
                </li>';
            }

            $podkategorije_meni .= '
                </ul>
            </section>';

        }

        // brandovi meni
        $brandovi = $this->artikli->brandovi($trenutna_kategorija['Link'], $trazi, $cijena_od, $cijena_do, $velicina);
        $brand_meni = '';
        foreach ($brandovi as $brand1) {
            $checked = in_array(mb_strtolower($brand1['Brand']), explode('+', $brand))
                ? 'checked': '';

            $rez = explode('+', $brand);
            if (($key = array_search(mb_strtolower($brand1['Brand']), $rez)) !== false) {
                unset($rez[$key]);
            } else {$rez[] = mb_strtolower($brand1['Brand']);}
            $rez = implode('+', $rez);

            $brand_meni .= '
                <li>
                    <label class="kontrolni_okvir">
                        <span>'.$brand1['Brand'].'</span>
                        <input type="checkbox" '.$checked.' data-url="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.$velicina.'/'.$rez.'/'.$poredaj.'/'.$poredaj_redoslijed.'/">
                        <span class="checkmark"></span>
                    </label>
                </li>
            ';
        }
        $brandovi_meni = "
        <ul class='panel'>
            <li><a href=\"/artikli/$kategorija/$trazi/$cijena_od/$cijena_do/sve/sve/$poredaj/$poredaj_redoslijed/\">Reset</a></li>
            $brand_meni
        </ul>
        ";

        // velicine meni
        $velicine = $this->artikli->velicine($trenutna_kategorija['Link'], $trazi, $cijena_od, $cijena_do, $brand);
        $velicina_meni = '';
        foreach ($velicine as $velicina1) {
            $checked = mb_strtolower($velicina1['Velicina']) === (string)$velicina
                ? 'checked': '';
            $velicina_meni .= '
                <li>
                    <label class="kontrolni_okvir">
                        <span>'.$velicina1['Velicina'].'</span>
                        <input type="checkbox" '.$checked.' data-url="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/'.mb_strtolower($velicina1['Velicina']).'/'.$brand.'/'.$poredaj.'/'.$poredaj_redoslijed.'/" data-url2="/artikli/'.$kategorija.'/'.$trazi.'/'.$cijena_od.'/'.$cijena_do.'/sve/'.$brand.'/'.$poredaj.'/'.$poredaj_redoslijed.'/">
                        <span class="checkmark"></span>
                    </label>
                </li>
            ';
        }
        $velicine_meni = "
        <ul class='panel'>
            <li><a href=\"/artikli/$kategorija/$trazi/$cijena_od/$cijena_do/sve/$brand/$poredaj/$poredaj_redoslijed/\">Reset</a></li>
            $velicina_meni
        </ul>
        ";

        $cijena = empty($cijena = array_column($artikli, 'Cijena')) ? [0] : $cijena;

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

        return sadrzaj()->datoteka('artikli.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => $trenutna_kategorija['Kategorija'],
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ '.$roditelji_html.' '.$trenutna_kategorija['Kategorija'],
            'kategorija_naziv' => $trenutna_kategorija['Kategorija'],
            'kategorija_opis' => $trenutna_kategorija['Opis'] ?? '',
            'artikli' => $artikli_html,
            'navigacija' => $navigacija_html,
            "poredaj_izbornik" => $poredaj_izbornik,
            "prikazujem" => $prikazujem,
            "podkategorije" => $podkategorije_meni,
            "brandovi_meni" => $brandovi_meni,
            "velicine_meni" => $velicine_meni,
            "cijena_min" => number_format((int)min($cijena)),
            "cijena_max" => number_format((int)max($cijena)),
            'izdvojeno' => $artikli_izdvojeno_html
        ]));

    }

}