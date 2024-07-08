<?php declare(strict_types = 1);

/**
 * Opći uvjeti
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

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;

/**
 * ### Naslovna
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class OpciUvjeti_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (BazaPodataka $bazaPodataka = null):Sadrzaj {

        return sadrzaj()->datoteka('opci_uvjeti.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'Opci uvjeti',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Opći uvjeti'
        ]));

    }

}