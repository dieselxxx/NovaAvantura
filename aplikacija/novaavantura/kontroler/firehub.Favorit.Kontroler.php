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
use FireHub\Aplikacija\NovaAvantura\Model\Favorit_Model;

/**
 * ### Favorit
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Favorit_Kontroler extends Master_Kontroler {

    protected Model $favoriti;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->favoriti = $this->model(Favorit_Model::class);

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
        $favorit_artikli = $this->favoriti->artikliFavorit();
        $artikli_html = '';
        if (!empty($favorit_artikli)) {

            foreach ($favorit_artikli as $artikal) {

                // artikli
                $artikli_html .= '
                    <form class="artikl" method="post" enctype="multipart/form-data" action="">
                        <input type="hidden" name="ID" value="'.$artikal['ID'].'">
                        <img src="/slika/malaslika/'.$artikal['Slika'].'" alt="" loading="lazy"/>
                        <a class="naslov" href="/artikl/'.$artikal['Link'].'">'.$artikal['Naziv'].'</a>
                        <span class="cijena">'.$artikl_cijena.'</span>
                        <div class="kosarica">
                            <button type="submit" class="gumb ikona" name="favorit_izbrisi">
                                <svg><use xlink:href="/kapriol/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg>
                                <span>Izbriši</span>
                            </button>
                        </div>
                    </form>
                ';

            }

        }

        return sadrzaj()->datoteka('artikl.html')->podatci(array_merge($this->zadaniPodatci(), [
            'greska' => '',
            'predlozak_naslov' => 'Favoriti',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Favoriti',
            'favorit_artikli' => $artikli_html
        ]));

    }

}