<?php declare(strict_types = 1);

/**
 * Brandovi model
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2024 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Model
 */

namespace FireHub\Aplikacija\NovaAvantura\Model;

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;

/**
 * ### Brandovi model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Brandovi_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.2.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ){

        parent::__construct();

    }

    /**
     * ### Dohvati brandove po linku
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string $link <p>
     * Link kategorije.
     * </p>
     *
     * @return array Brand.
     */
    public function brandPoLinku (string $link):array {

        return $this->bazaPodataka->tabela('brandovi')
            ->odaberi(['ID', 'Brand', 'Opis', 'Slika'])
            ->gdje('Brand', '=', $link)
            ->napravi()->redak() ?: [
                'ID' => 0, 'Brand' => 'Brand ne postoji', 'Opis' => '', 'Slika' => ''
            ];

    }

}