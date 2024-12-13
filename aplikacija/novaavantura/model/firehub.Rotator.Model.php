<?php declare(strict_types = 1);

/**
 * Rotator model
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

use FireHub\Aplikacija\NovaAvantura\Jezgra\Domena;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;

/**
 * ### Rotator model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Rotator_Model extends Master_Model {

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
     * ### Slike za rotator
     * @since 0.1.0.pre-alpha.M1
     *
     * @return array
     */
    public function slike ():array {

        return $this->bazaPodataka->tabela('obavijesti')
            ->sirovi("
                SELECT 
                    Obavijest, Obavijest2, artikliview.Link, obavijesti.Link".Domena::sqlTablica()." AS URL
                FROM obavijesti
                LEFT JOIN artikliview ON artikliview.ID = obavijesti.ArtikalID
                WHERE obavijesti.".Domena::sqlTablica()." = 1
                ORDER BY obavijesti.Redoslijed
            ")
            ->napravi()
            ->niz() ?: [];

    }

}