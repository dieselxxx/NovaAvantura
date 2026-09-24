<?php declare(strict_types = 1);

/**
 * Artikl model
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
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Sidrena cijena CSV
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class SidrenaCijenaCSV_Model extends Master_Model {

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
     * ### Artikli
     * @since 0.1.2.pre-alpha.M1
     *
     * @throws Kontejner_Greska
     *
     * @return bool
     */
    public function artikli ():mixed {

        $rezultat = $this->bazaPodataka
            ->sirovi("
                SELECT
                    Naziv,
                    CijenaKn as Cijena
                FROM artikli
                WHERE ID <> 0
                AND Aktivan = 1
                AND Hr = 1
                ORDER BY ID DESC
            ")
            ->napravi()->niz() ?: [];

        foreach ($rezultat as $kljuc => $redak) {

            $rezultat[$kljuc]['Cijena'] = number_format((float)$redak['Cijena'], 2, ',', '.');

        }

        return $rezultat;

    }

}