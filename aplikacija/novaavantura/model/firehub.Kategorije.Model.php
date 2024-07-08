<?php declare(strict_types = 1);

/**
 * Kategorije model
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
use FireHub\Aplikacija\NovaAvantura\Jezgra\Domena;

/**
 * ### Kategorije model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Kategorije_Model extends Master_Model {

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
     * ### Sve kategorije
     * @since 0.1.1.pre-alpha.M1
     *
     * @return array Niz kategorija.
     */
    public function kategorije ():array {

        return $this->bazaPodataka->tabela('kategorijeview')
            ->sirovi("
                SELECT 
                    kategorijeview.ID, kategorijeview.Kategorija, kategorijeview.Link, kategorijeview.Slika, kategorijeview.Roditelj
                FROM kategorijeview
            ")->napravi()->niz();

        return $this->bazaPodataka->tabela('kategorijeview')
            ->sirovi("
                SELECT 
                    kategorijeview.Kategorija, kategorijeview.Link, kategorijeview.Slika
                FROM kategorijeview
                LEFT JOIN artikli ON artikli.KategorijaID = kategorijeview.ID AND artikli.".Domena::sqlTablica()." = 1
                LEFT JOIN artiklikarakteristike ON artiklikarakteristike.ArtikalID = artikli.ID
                LEFT JOIN stanjeskladista ON stanjeskladista.Sifra = artiklikarakteristike.Sifra
                GROUP BY kategorijeview.Kategorija, kategorijeview.Link, kategorijeview.Slika
                HAVING SUM(StanjeSkladiste) > 0
            ")->napravi()->niz();

    }

}