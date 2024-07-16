<?php declare(strict_types = 1);

/**
 * Favoriti model
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
 * ### Favoriti model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Favoriti_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @param BazaPodataka $bazaPodataka <p>
     * Baza podataka.
     * </p>
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ) {

        parent::__construct();

    }

    /**
     * ### Dodaj favorita
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id
     */
    public function dodaj (int $id) {

        if (!isset($this->sesija->procitaj('favorit')[$id])) {

            $this->sesija->dodaj('favorit', (string)$id, $id);

        }

    }

    /**
     * ### Izbriši favorita
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id
     *
     * @return bool
     */
    public function izbrisi (int $id):bool {

        if (!isset($this->sesija->procitaj('favorit')[$id])) {

            return false;

        }

        $this->sesija->izbrisiNiz('favorit', (string)$id);

        return true;

    }

    /**
     * ### Artikala favorita
     * @since 0.1.0.pre-alpha.M1
     *
     * @return array
     */
    public function artikli ():array {

        return $this->sesija->procitaj('favorit');

    }

    /**
     * ### Broj artikala favorita
     * @since 0.1.0.pre-alpha.M1
     *
     * @return int
     */
    public function brojArtikala ():int {

        if ($this->sesija->procitaj('favorit')) {

            return count($this->sesija->procitaj('favorit'));

        }

        return 0;

    }

    /**
     * ### Artikli iz favorita
     * @since 0.1.0.pre-alpha.M1
     *
     * @return array Niz artikala.
     */
    public function artikliFavorit ():array {

        if ($this->sesija->procitaj('favorit')) {

            $id = array_keys($this->sesija->procitaj('favorit'));

            $sifra_array = '';
            foreach ($id as $kljuc => $vrijednost) {

                if ($kljuc === array_key_first($id)) {

                    $sifra_array .= "
                        artikliview.ID = $vrijednost
                    ";

                } else {

                    $sifra_array .= "
                        OR artikliview.ID = $vrijednost
                    ";

                }

            }

            $artikli = $this->bazaPodataka->tabela('artikliview')
                ->sirovi("
                    SELECT
                           artikliview.ID, artikliview.Naziv, artikliview.Link,
                           artikliview.".Domena::sqlCijena()." AS Cijena, artikliview.".Domena::sqlCijenaAkcija()." AS CijenaAkcija,
                           (SELECT Slika FROM slikeartikal WHERE slikeartikal.ClanakID = artikliview.ID ORDER BY slikeartikal.Zadana DESC LIMIT 1) AS Slika
                    FROM artikliview
                    WHERE artikliview.Aktivan = 1 AND artikliview.".Domena::sqlTablica()." = 1
                    AND ($sifra_array)
                    ORDER BY Naziv ASC
                ")
                ->napravi();

            $rezultat = $artikli->niz();

            return $rezultat ?: [];

        }

        return [];

    }

}