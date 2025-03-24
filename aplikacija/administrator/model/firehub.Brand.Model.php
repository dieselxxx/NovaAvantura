<?php declare(strict_types = 1);

/**
 * Brand model
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
 * ### Brand
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Brand_Model extends Master_Model {

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
     * ### Brand
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id
     *
     * @throws Kontejner_Greska
     * @return array|false|mixed[]
     */
    public function brand (int $id):array|false {

        $brand = $this->bazaPodataka
            ->sirovi("
                SELECT
                    brandovi.ID, brandovi.Brand, brandovi.Opis, brandovi.Slika
                FROM brandovi
                WHERE brandovi.ID = $id
                LIMIT 1
            ")
            ->napravi();

        return $brand->redak();

    }

    /**
     * ### Spremi brand
     * @since 0.1.0.pre-alpha.M1
     */
    public function spremi (int $id) {

        $id = Validacija::Broj(_('ID branda'), $id, 1, 10);

        $naziv = $_REQUEST['naziv'];
        $naziv = Validacija::String(_('Naziv branda'), $naziv, 3, 250);

        if (!empty($_REQUEST['opis'])) {
            $opis = $_REQUEST['opis'];
            $opis = Validacija::String(_('Opis branda'), $opis, 1, 1000);
        }

        if ($id !== 0) {

            $this->bazaPodataka->tabela('brandovi')->azuriraj([
                'Brand' => $naziv,
                'Opis' => $opis ?? ''
            ])->gdje(
                'ID', '=', $id
            )->napravi();

        } else {

            $this->bazaPodataka->tabela('brandovi')->umetni([
                'Brand' => $naziv
            ])->napravi();

        }

    }

    /**
     * ### Izbrisi brand
     * @since 0.1.0.pre-alpha.M1
     */
    public function izbrisi (int $id) {

        $id = Validacija::Broj(_('ID branda'), $id, 1, 10);

        $broj = $this->bazaPodataka
            ->sirovi("
                SELECT *
                FROM artikli
                WHERE BrandID = $id
            ")
            ->napravi();

        if ($broj->broj_zapisa() > 0) {

            throw new Greska('Ne možete izbrisati brand jer imate artikala u njoj!');

        }

        $putanja = FIREHUB_ROOT.konfiguracija('sustav.putanje.web').'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'brandovi'.RAZDJELNIK_MAPE;
        $rezultat = $this->bazaPodataka->tabela('brandovi')->odaberi([
            'Slika'
        ])->gdje(
            'ID', '=', $id
        )->napravi()->redak();
        @unlink($putanja.$rezultat['Slika']);

        $this->bazaPodataka->tabela('brandovi')->izbrisi()->gdje(
            'ID', '=', $id
        )->napravi();

    }

    /**
     * ### Dodaj sliku branda
     * @since 0.1.0.pre-alpha.M1
     */
    public function dodajSliku (int $id) {

        $putanja = FIREHUB_ROOT.konfiguracija('sustav.putanje.web').'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'brandovi'.RAZDJELNIK_MAPE;

        // prenesi sliku
        $datoteka = new PrijenosDatoteka('slika');
        $datoteka->Putanja($putanja);
        $datoteka->DozvoljeneVrste(array('image/jpeg', 'image/png', 'image/webp'));
        $datoteka->DozvoljenaVelicina(3000);
        $datoteka->NovoIme();
        $datoteka->PrijenosDatoteke();
        $datoteka->SlikaDimenzije(600, 300);

        $rezultat = $this->bazaPodataka->tabela('brandovi')->odaberi([
            'Slika'
        ])->gdje(
            'ID', '=', $id
        )->napravi()->redak();

        @unlink($putanja.$rezultat['Slika']);

        $this->bazaPodataka->tabela('brandovi')->azuriraj([
            'Slika' => $datoteka->ImeDatoteke()
        ])->gdje(
            'ID', '=', $id
        )->napravi();

    }

}