<?php declare(strict_types = 1);

/**
 * Blog model
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

use DateTime;
use FireHub\Aplikacija\Administrator\Jezgra\PrijenosDatoteka;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Validacija;
use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Blog
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Blog_Model extends Master_Model {

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
     * ### Blog
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id
     *
     * @throws Kontejner_Greska
     * @return array|false|mixed[]
     */
    public function blog (int $id):array|false {

        $blog = $this->bazaPodataka
            ->sirovi("
                SELECT
                    blog.ID, blog.Naslov, blog.Opis, blog.Datum, blog.Slika
                FROM blog
                WHERE blog.ID = $id
                LIMIT 1
            ")
            ->napravi();

        return $blog->redak();

    }

    /**
     * ### Spremi blog
     * @since 0.1.0.pre-alpha.M1
     */
    public function spremi (int $id) {

        $id = Validacija::Broj(_('ID bloga'), $id, 1, 10);

        $naslov = $_REQUEST['naslov'];
        $naslov = Validacija::String(_('Naslov bloga'), $naslov, 3, 250);

        if (!empty($_REQUEST['opis'])) {
            $opis = $_REQUEST['opis'];
            $opis = Validacija::String(_('Opis bloga'), $opis, 1, 5000);
        }

        if ($id !== 0) {

            $this->bazaPodataka->tabela('blog')->azuriraj([
                'Naslov' => $naslov,
                'Opis' => $opis ?? ''
            ])->gdje(
                'ID', '=', $id
            )->napravi();

        } else {

            $this->bazaPodataka->tabela('blog')->umetni([
                'Naslov' => $naslov,
                'Datum' => (new \DateTime())->format('Y-m-d')
            ])->napravi();

        }

    }

    /**
     * ### Izbrisi blog
     * @since 0.1.0.pre-alpha.M1
     */
    public function izbrisi (int $id) {

        $id = Validacija::Broj(_('ID bloga'), $id, 1, 10);

        $putanja = FIREHUB_ROOT.konfiguracija('sustav.putanje.web').'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'blog'.RAZDJELNIK_MAPE;
        $rezultat = $this->bazaPodataka->tabela('blog')->odaberi([
            'Slika'
        ])->gdje(
            'ID', '=', $id
        )->napravi()->redak();
        @unlink($putanja.$rezultat['Slika']);

        $this->bazaPodataka->tabela('blog')->izbrisi()->gdje(
            'ID', '=', $id
        )->napravi();

    }

    /**
     * ### Dodaj sliku bloga
     * @since 0.1.0.pre-alpha.M1
     */
    public function dodajSliku (int $id) {

        $putanja = FIREHUB_ROOT.konfiguracija('sustav.putanje.web').'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'blog'.RAZDJELNIK_MAPE;

        // prenesi sliku
        $datoteka = new PrijenosDatoteka('slika');
        $datoteka->Putanja($putanja);
        $datoteka->DozvoljeneVrste(array('image/jpeg', 'image/png', 'image/webp'));
        $datoteka->DozvoljenaVelicina(3000);
        $datoteka->NovoIme();
        $datoteka->PrijenosDatoteke();
        $datoteka->SlikaDimenzije(800, 500);

        $rezultat = $this->bazaPodataka->tabela('blog')->odaberi([
            'Slika'
        ])->gdje(
            'ID', '=', $id
        )->napravi()->redak();

        @unlink($putanja.$rezultat['Slika']);

        $this->bazaPodataka->tabela('blog')->azuriraj([
            'Slika' => $datoteka->ImeDatoteke()
        ])->gdje(
            'ID', '=', $id
        )->napravi();

    }

}