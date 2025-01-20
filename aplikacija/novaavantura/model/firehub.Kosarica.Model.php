<?php declare(strict_types = 1);

/**
 * Košarica model
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
use FireHub\Jezgra\Kontroler\Greske\Kontroler_Greska;

/**
 * ### Košarica model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Kosarica_Model extends Master_Model {

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
     * ### Dodaj artikl u košaricu
     * @since 0.1.0.pre-alpha.M1
     *
     * @return bool Da li je artikl dodan u košaricu.
     */
    public function dodaj (string $velicina = '', int $vrijednost = 0):bool {

        // provjera zaliha
        $velicina_baza = $this->bazaPodataka->tabela('artiklikarakteristike')
            ->sirovi("
                SELECT
                    SUM(StanjeSkladiste) AS StanjeSkladiste
                FROM artiklikarakteristike
                LEFT JOIN stanjeskladista ON stanjeskladista.Sifra = artiklikarakteristike.Sifra
                LEFT JOIN skladiste ON skladiste.ID = stanjeskladista.SkladisteID
                WHERE artiklikarakteristike.Sifra = '$velicina'
                AND skladiste.Drzava = ".Domena::drzavaID()."
                GROUP BY Velicina
            ")
            ->napravi();

        if ((int)$velicina_baza->redak()['StanjeSkladiste'] < 1) {

            zapisnik(Level::KRITICNO, sprintf(_('Šifre artikla: "%s" nema na stanju!'), $velicina_baza));
            throw new Kontroler_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        if (!$vrijednost > 0) return false;

        if (isset($this->sesija->procitaj('kosarica')[$velicina]))
            $this->sesija->dodaj('kosarica', $velicina, $vrijednost + $this->sesija->procitaj('kosarica')[$velicina]);
        else
            $this->sesija->dodaj('kosarica', $velicina, $vrijednost);

        return true;

    }

    /**
     * ### Dodaj artikl u košaricu
     * @since 0.1.0.pre-alpha.M1
     *
     * @return bool Da li je artikl dodan u košaricu.
     */
    public function izmijeni (string $velicina = '', int $vrijednost = 0):bool {

        $velicina_baza = $this->bazaPodataka->tabela('artiklikarakteristike')
            ->sirovi("
                SELECT
                    SUM(StanjeSkladiste) AS StanjeSkladiste
                FROM artiklikarakteristike
                LEFT JOIN stanjeskladista ON stanjeskladista.Sifra = artiklikarakteristike.Sifra
                WHERE artiklikarakteristike.Sifra = '$velicina'
                GROUP BY Velicina
            ")
            ->napravi();

        if (!$velicina_baza->redak()['StanjeSkladiste'] > 1) {

            zapisnik(Level::KRITICNO, sprintf(_('Šifre artikla: "%s" nema na stanju!'), $velicina_baza));
            throw new Kontroler_Greska(_('Ne mogu pokrenuti sustav, obratite se administratoru.'));

        }

        if (!$vrijednost > 0) return false;

        if (!isset($this->sesija->procitaj('kosarica')[$velicina]))
            return false;

        var_dump($this->sesija->procitaj('kosarica')[$velicina]);

        $this->sesija->dodaj('kosarica', $velicina, $vrijednost);

        return true;

    }

    /**
     * ### Izbriši artikl u košaricu
     * @since 0.1.0.pre-alpha.M1
     *
     * @return bool Da li je artikl izbrisan iz košarice.
     */
    public function izbrisi (string $velicina = ''):bool {

        if (!isset($this->sesija->procitaj('kosarica')[$velicina])) return false;

        $this->sesija->izbrisiNiz('kosarica', $velicina);

        return true;

    }

    /**
     * ### Artikala košarice
     * @since 0.1.0.pre-alpha.M1
     *
     * @return array
     */
    public function artikli ():array {

        return $this->sesija->procitaj('kosarica') ?: [];

    }

    /**
     * ### ID artikla
     * @since 0.1.0.pre-alpha.M1
     *
     * @return array
     */
    public function artikliID ():array {

        $rezultat = [];

        foreach ($this->artikli() as $velicina => $kolicina) {

            $redak = $this->bazaPodataka->tabela('artiklikarakteristike')
                ->sirovi("
                SELECT
                    ID, ArtikalID, Velicina
                FROM artiklikarakteristike
                WHERE artiklikarakteristike.Sifra = '$velicina'
            ")
                ->napravi()->redak();

            $rezultat[$redak['ID']] = [
                'id' => $redak['ArtikalID'], 'kolicina' => $kolicina, 'velicina' => $velicina,
                'velicinaNaziv' => $redak['Velicina']
            ];

        }


        return $rezultat;

    }

    /**
     * ### Broj artikala košarice
     * @since 0.1.0.pre-alpha.M1
     *
     * @return int
     */
    public function brojArtikala ():int {

        if ($this->sesija->procitaj('kosarica')) {

            return count($this->sesija->procitaj('kosarica'));

        }

        return 0;

    }

    /**
     * ### Unisti sesiju.
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function unistiSesiju ():void {

        $this->sesija->unisti();

    }

}