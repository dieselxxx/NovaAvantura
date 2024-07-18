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

/**
 * ### Košarica
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Kosarica_Kontroler extends Master_Kontroler {

    protected Model $kosarica;
    protected Model $artikli;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->kosarica = $this->model(Kosarica_Model::class);
        $this->artikli = $this->model(Artikli_Model::class);

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
        $artikli = $this->artikli->artikli('sve kategorije', 0, PHP_INT_MAX, 'svi artikli', 0, PHP_INT_MAX, 'sve', 'sve', 'cijena', 'asc');
        $kosarica_artikli = $this->kosarica->artikliID();
        $artikli_html = '';
        if (!empty($kosarica_artikli)) {

            foreach ($kosarica_artikli as $kosarica_artikal) {

                $artikal = array_filter($artikli, function ($value) use ($kosarica_artikal) {
                    return $value['ID'] === $kosarica_artikal['id'];
                });
                $artikal = $artikal[array_key_first($artikal)];

                $artikli_html .= <<<Artikal
            
                <form class="artikal" method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="ID" value="{$artikal['ID']}" />
                    <input type="hidden" name="velicina" value="{$kosarica_artikal['velicina']}">
                    <a class="slika" href="/artikl/{$artikal['Link']}">
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    </a>
                    <a class="naziv" href="/artikl/{$artikal['Link']}">{$artikal['Naziv']}</a>
                    <span class="cijena">{$artikal['CijenaHTML']}</span>
                    <span class="kosarica">
                        <label class="input">
                            <input type="number" name="vrijednost" data-pakiranje="1" data-maxpakiranje="1000" value="{$kosarica_artikal['kolicina']}" min="1" max="100" step="1" autocomplete="off" pattern="0-9">
                        </label>
                        <button type="button" class="gumb minus" onclick="ArtikalPlusMinus(this, 'minus');">-</button>
                        <button type="button" class="gumb plus" onclick="ArtikalPlusMinus(this, 'plus');">+</button>
                    </span>
                    <button type="submit" class="gumb ikona" name="kosarica_izmijeni">
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#uredi"></use></svg>
                        <span>Izmijeni</span>
                    </button>
                    <button type="submit" class="gumb ikona" name="kosarica_izbrisi">
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg>
                        <span>Izbriši</span>
                    </button>
                    <span>{$kosarica_artikal['velicina']}</span>
                </form>

            Artikal;


            }

        }

        return sadrzaj()->datoteka('kosarica.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'Košarica',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Košarica',
            'artikli' => empty($artikli_html) ? '<h2>Vaša košarica je prazna!</h2>' : $artikli_html
        ]));

    }

}