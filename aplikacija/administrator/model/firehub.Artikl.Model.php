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

namespace FireHub\Aplikacija\Administrator\Model;

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Validacija;
use FireHub\Aplikacija\Administrator\Jezgra\PrijenosDatoteka;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Artikl
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Artikl_Model extends Master_Model {

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
     * ### Dohvati karakteristike artikla
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string|int $id <p>
     * ID artikla.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca objekta.
     *
     * @return array Artikl.
     */
    public function artiklKarakteristike (string|int $id):array {

        $karakteristike = $this->bazaPodataka->tabela('artiklikarakteristike')
            ->sirovi("
                SELECT
                    artiklikarakteristike.ID, artiklikarakteristike.Sifra AS artiklikarakteristikeSifra, Velicina
                FROM artiklikarakteristike
                LEFT JOIN stanjeskladista ON stanjeskladista.Sifra = artiklikarakteristike.Sifra
                WHERE ArtikalID = '$id'
                GROUP BY Velicina
                ORDER BY artiklikarakteristike.ID
            ")
            ->napravi();

        return $karakteristike->niz() ?: [];

    }

    /**
     * ### Spremi šifre artikla
     * @since 0.1.0.pre-alpha.M1
     */
    public function artiklsifrespremi (int $id) {

        $id = Validacija::Broj(_('ID artikla'), $id, 1, 10);

        foreach ($_POST['zaliha'] as $karakeristikaID => $sifra) {

            $karakeristikaID = Validacija::Broj(_('ID šifre'), $karakeristikaID, 1, 10);
            $sifra = Validacija::String(_('Šifra artikla'), $sifra, 1, 50);
            $velicina = Validacija::String(_('Veličina artikla'), $_POST['velicina'][$karakeristikaID], 1, 50);

            $postoji = $this->bazaPodataka
                ->sirovi("
                    SELECT count(*) AS broj FROM artiklikarakteristike WHERE ID = '$karakeristikaID' AND ArtikalID = '$id'
                    ")
                ->napravi();

            if ($postoji->redak()['broj'] == '0') {

                $sql = $this->bazaPodataka
                    ->sirovi("
                        INSERT INTO artiklikarakteristike (ArtikalID, Sifra, Velicina) VALUES ('$id', '$sifra', '$velicina')
                    ")
                    ->napravi();

            } else {

                $sql = $this->bazaPodataka
                    ->sirovi("
                        UPDATE artiklikarakteristike SET Sifra = '$sifra', Velicina = '$velicina' WHERE ID = '$karakeristikaID' AND ArtikalID = '$id'
                    ")
                    ->napravi();

            }

        }

    }

    /**
     * ### Izbriši šifru artikla
     * @since 0.1.0.pre-alpha.M1
     */
    public function artiklsifreizbrisi (int $id) {

        $id = Validacija::Broj(_('ID artikla'), $id, 1, 10);

        $sql = $this->bazaPodataka
            ->sirovi("
                DELETE FROM artiklikarakteristike WHERE ID = '$id'
            ")
            ->napravi();

    }

    /**
     * ### Artikl zaliha
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id
     *
     * @throws Kontejner_Greska
     * @return array|false|mixed[]
     */
    public function artiklzaliha (string|int $id, string|int $skladiste):array|false {

        $artikl = $this->bazaPodataka
            ->sirovi("
                SELECT
                    skladiste.NazivSkladista, stanjeskladista.StanjeSkladiste
                FROM stanjeskladista
                LEFT JOIN skladiste ON skladiste.ID = stanjeskladista.SkladisteID
                LEFT JOIN artiklikarakteristike ON artiklikarakteristike.Sifra = stanjeskladista.Sifra
                LEFT JOIN artikli ON artikli.ID = artiklikarakteristike.ArtikalID
                WHERE stanjeskladista.Sifra = '$id' AND skladiste.ID = '$skladiste'
                LIMIT 1
            ")
            ->napravi();

        $artikl = $artikl->redak();

        return $artikl;

    }

    /**
     * ### Skladiste
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id
     *
     * @throws Kontejner_Greska
     * @return array|false|mixed[]
     */
    public function skladista ():array {

        $karakteristike = $this->bazaPodataka->tabela('skladiste')
            ->sirovi("
                SELECT
                    ID, NazivSkladista
                FROM skladiste
                ORDER BY ID
            ")
            ->napravi();

        return $karakteristike->niz();

    }

    /**
     * ### Spremi zalihu artikla
     * @since 0.1.0.pre-alpha.M1
     */
    public function zalihaspremi (int $id) {

        $id = Validacija::Broj(_('ID artikla'), $id, 1, 10);

        foreach ($_POST['zaliha'] as $karakteristika => $skladiste) {

            foreach ($skladiste as $skladisteID => $vrijednost) {

                $postoji = $this->bazaPodataka
                    ->sirovi("
                    SELECT count(*) AS broj FROM stanjeskladista WHERE SkladisteID = '$skladisteID' AND Sifra = '$karakteristika'
                    ")
                    ->napravi();

                if ($postoji->redak()['broj'] == '0') {

                    $sql = $this->bazaPodataka
                        ->sirovi("
                            INSERT INTO stanjeskladista (SkladisteID, Sifra, StanjeSkladiste) VALUES ('$skladisteID', '$karakteristika', '$vrijednost')
                        ")
                        ->napravi();

                } else {

                    $sql = $this->bazaPodataka
                        ->sirovi("
                        UPDATE stanjeskladista SET StanjeSkladiste = '$vrijednost' WHERE SkladisteID = '$skladisteID' AND Sifra = '$karakteristika'
                        ")
                        ->napravi();

                }

            }

        }

    }

}