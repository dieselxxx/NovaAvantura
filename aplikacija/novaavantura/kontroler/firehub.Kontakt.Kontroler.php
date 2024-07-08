<?php declare(strict_types = 1);

/**
 * Kontakt
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

/**
 * ### Kontakt
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Kontakt_Kontroler extends Master_Kontroler {

    /**
     * ### index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        return sadrzaj()->datoteka('kontakt.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'Kontakt',
            'vi_ste_ovdje' => '<a href="/">Naslovna</a> \\ Kontakt'
        ]));

    }

}