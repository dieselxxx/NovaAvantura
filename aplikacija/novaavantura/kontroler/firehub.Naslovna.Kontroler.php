<?php declare(strict_types = 1);

/**
 * Naslovna
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

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\NovaAvantura\Model\Rotator_Model;

/**
 * ### Naslovna
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Naslovna_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (BazaPodataka $bazaPodataka = null):Sadrzaj {

        $rotator = $this->model(Rotator_Model::class);

        // rotator
        $obavijest_html = '';
        foreach ($rotator->slike() as $obavijest) {

            $link = empty($obavijest['URL'])
                ? $obavijest['Link']
                    ? 'href="/artikl/'.$obavijest['Link'].'"'
                    : ''
                : 'href="'.$obavijest['URL'].'"';

            $obavijest_html .= "
            <a class='swiper-slide' $link>
                <img
                    srcset=\"
                        /slika/baner/{$obavijest['Obavijest']}/550/1920 1000w,
                        /slika/baner/{$obavijest['Obavijest']}/286/1000 768w,
                        /slika/baner/{$obavijest['Obavijest']}/220/768 600w\"
                    src=\"/slika/baner/{$obavijest['Obavijest']}/550/1920 1000w\"
                    alt=\"\" loading=\"lazy\"
                />
            </a>
            ";

        }

        return sadrzaj()->datoteka('naslovna.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'Naslovna',
            'obavijesti' => $obavijest_html
        ]));

    }

}