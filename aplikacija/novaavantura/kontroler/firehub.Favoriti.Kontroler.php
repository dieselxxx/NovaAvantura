<?php declare(strict_types = 1);

/**
 * Favorit
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

use FireHub\Jezgra\Model\Model;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\NovaAvantura\Model\Favoriti_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Artikli_Model;

/**
 * ### Favorit
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Favoriti_Kontroler extends Master_Kontroler {

    protected Model $favoriti;
    protected Model $artikli;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->favoriti = $this->model(Favoriti_Model::class);
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

        // favoriti
        $artikli = $this->artikli->artikli('sve kategorije', 0, PHP_INT_MAX, 'svi artikli', 0, PHP_INT_MAX, 'sve', 'sve', 'cijena', 'asc');
        $favorit_artikli = $this->favoriti->artikli();
        $artikli_html = '';
        if (!empty($favorit_artikli)) {

            $favorit_artikli = array_filter($artikli, function ($value) use ($favorit_artikli) {
                return in_array($value['ID'], $favorit_artikli) ;
            });

            foreach ($favorit_artikli as $artikal) {

                $artikli_html .= <<<Artikal
            
                <form class="artikal" method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="ID" value="{$artikal['ID']}" />
                    <a class="slika" href="/artikl/{$artikal['Link']}">
                        {$artikal['PopustHTML']}
                        {$artikal['NovoHTML']}
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    </a>
                    <span class="brand">
                        <button type="submit" class="gumb ikona" name="favorit_izbrisi">
                            <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti"></use></svg>
                            <span>Ukloni</span>
                        </button>
                        {$artikal['Brand']}
                     </span>
                    <a class="naziv" href="/artikl/{$artikal['Link']}">{$artikal['Naziv']}</a>
                    <span class="cijena">{$artikal['CijenaHTML']}</span>
                    <span class="cijena_30_dana">{$artikal['Cijena30DanaHTML']}</span>
                </form>

            Artikal;

            }

        }

        return sadrzaj()->datoteka('favoriti.html')->podatci(array_merge($this->zadaniPodatci(), [
            'greska' => '',
            'predlozak_naslov' => 'Favoriti',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Favoriti',
            'favorit_artikli' => $artikli_html
        ]));

    }

}