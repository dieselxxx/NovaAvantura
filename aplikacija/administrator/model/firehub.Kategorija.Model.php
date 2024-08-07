<?php declare(strict_types = 1);

/**
 * Kategorija model
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

use FireHub\Aplikacija\Administrator\Jezgra\PrijenosDatoteka;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Validacija;
use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Kategorija
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Kategorija_Model extends Master_Model {

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
     * ### Kategorija
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id
     *
     * @throws Kontejner_Greska
     * @return array|false|mixed[]
     */
    public function kategorija (int $id):array|false {

        $kategorija = $this->bazaPodataka
            ->sirovi("
                SELECT
                    kategorije.ID, kategorije.Kategorija, kategorije.Opis, kategorije.Slika,
                    ifnull(roditelj.ID, '') AS RoditeljID,
                    ifnull(roditelj.Kategorija, '') AS Roditelj
                FROM kategorije
                LEFT JOIN kategorije roditelj ON roditelj.ID = kategorije.Roditelj
                WHERE kategorije.ID = $id
                LIMIT 1
            ")
            ->napravi();

        return $kategorija->redak();

    }

    /**
     * ### Spremi kategoriju
     * @since 0.1.0.pre-alpha.M1
     */
    public function spremi (int $id) {

        $id = Validacija::Broj(_('ID kategorije'), $id, 1, 10);

        $naziv = $_REQUEST['naziv'];
        $naziv = Validacija::String(_('Naziv kategorije'), $naziv, 3, 250);

        if (!empty($_REQUEST['opis'])) {
            $opis = $_REQUEST['opis'];
            $opis = Validacija::String(_('Opis kategorije'), $opis, 1, 1000);
        }

        if (!empty($_REQUEST['roditelj'])) {
            $roditelj = $_REQUEST['roditelj'];
            $roditelj = Validacija::Broj(_('Roditelj'), $roditelj, 1, 11);
        }

        if ($id !== 0) {

            $this->bazaPodataka->tabela('kategorije')->azuriraj([
                'Kategorija' => $naziv,
                'Opis' => $opis ?? '',
                'Roditelj' => $roditelj ?? 0
            ])->gdje(
                'ID', '=', $id
            )->napravi();

        } else {

            $this->bazaPodataka->tabela('kategorije')->umetni([
                'Kategorija' => $naziv,
                'Roditelj' => $roditelj ?? 0
            ])->napravi();

        }

    }

    /**
     * ### Izbrisi kategoriju
     * @since 0.1.0.pre-alpha.M1
     */
    public function izbrisi (int $id) {

        $id = Validacija::Broj(_('ID kategorije'), $id, 1, 10);

        $broj = $this->bazaPodataka
            ->sirovi("
                SELECT *
                FROM artikli
                WHERE KategorijaID = $id
            ")
            ->napravi();

        if ($broj->broj_zapisa() > 0) {

            throw new Greska('Ne možete izbrisati kategoriju jer imate artikala u njoj!');

        }

        $broj = $this->bazaPodataka
            ->sirovi("
                SELECT *
                FROM kategorije
                WHERE Roditelj = $id
            ")
            ->napravi();

        if ($broj->broj_zapisa() > 0) {

            throw new Greska('Ne možete izbrisati kategoriju jer kategorija ima podkategorije!');

        }

        $putanja = FIREHUB_ROOT.konfiguracija('sustav.putanje.web').'novaavantura'.RAZDJELNIK_MAPE.'resursi' .RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'kategorije'.RAZDJELNIK_MAPE;
        $rezultat = $this->bazaPodataka->tabela('kategorije')->odaberi([
            'Slika'
        ])->gdje(
            'ID', '=', $id
        )->napravi()->redak();
        @unlink($putanja.$rezultat['Slika']);

        $this->bazaPodataka->tabela('kategorije')->izbrisi()->gdje(
            'ID', '=', $id
        )->napravi();

    }

    /**
     * ### Dodaj sliku kategorije
     * @since 0.1.0.pre-alpha.M1
     */
    public function dodajSliku (int $id) {

        $putanja = FIREHUB_ROOT.konfiguracija('sustav.putanje.web').'novaavantura'.RAZDJELNIK_MAPE.'resursi' .RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'kategorije'.RAZDJELNIK_MAPE;

        // prenesi sliku
        $datoteka = new PrijenosDatoteka('slika');
        $datoteka->Putanja($putanja);
        $datoteka->DozvoljeneVrste(array('image/jpeg', 'image/png', 'image/webp'));
        $datoteka->DozvoljenaVelicina(1000);
        $datoteka->NovoIme();
        $datoteka->PrijenosDatoteke();
        $datoteka->SlikaDimenzije(800, 600);

        $rezultat = $this->bazaPodataka->tabela('kategorije')->odaberi([
            'Slika'
        ])->gdje(
            'ID', '=', $id
        )->napravi()->redak();

        @unlink($putanja.$rezultat['Slika']);

        $this->bazaPodataka->tabela('kategorije')->azuriraj([
            'Slika' => $datoteka->ImeDatoteke()
        ])->gdje(
            'ID', '=', $id
        )->napravi();

    }

}
