<?php declare(strict_types = 1);

/**
 * Sesija model
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2024 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Model
 */

namespace FireHub\Aplikacija\Administrator\Model;

use FireHub\Aplikacija\NovaAvantura\Jezgra\Server;

/**
 * ### Sesija model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Sesija_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.0.pre-alpha.M1
     */
    public function __construct () {

        parent::__construct();

        if (!$this->sesija->procitaj('korisnik')) {

            header("Location: ".Server::URL()."/administrator/prijava");

        }

    }

}