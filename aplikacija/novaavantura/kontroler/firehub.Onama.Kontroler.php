<?php declare(strict_types = 1);

/**
 * O nama
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

/**
 * ### O nama
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Onama_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (BazaPodataka $bazaPodataka = null):Sadrzaj {

        return sadrzaj()->datoteka('o_nama.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'O nama',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ O nama'
        ]));

    }

}