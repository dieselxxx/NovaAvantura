<?php declare(strict_types = 1);

/**
 * Brandovi
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2024 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\Administrator\Kontroler;

use FireHub\Aplikacija\Administrator\Model\Brand_Model;
use FireHub\Aplikacija\Administrator\Model\Brandovi_Model;
use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Sadrzaj\Enumeratori\Vrsta as Sadrzaj_Vrsta;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;

/**
 * ### Brandovi
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Brandovi_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        return sadrzaj()->datoteka('brandovi/lista.html')->podatci([]);

    }

    /**
     * ## Lista brandova
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function lista (string $kontroler = '', string $metoda = '', int $broj_stranice = 1, string $poredaj = 'Brand', string $redoslijed = 'asc'):Sadrzaj {

        try {

            // model
            $brandovi = $this->model(Brandovi_Model::class);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'Brandovi' => $brandovi->lista($broj_stranice, $poredaj, $redoslijed),
                'Zaglavlje' => $brandovi->IspisiZaglavlje(),
                'Navigacija' => $brandovi->IspisiNavigaciju()
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

    /**
     * ## Uredi brandove
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function uredi (string $kontroler = '', string $metoda = '', int $id = 0) {

        $brand_model = $this->model(Brand_Model::class);
        $brand = $brand_model->brand($id);

        return sadrzaj()->format(Sadrzaj_Vrsta::HTMLP)->datoteka('brandovi/uredi.html')->podatci([
            'id' => $brand['ID'],
            'naziv' => $brand['Brand'],
            'opis' => $brand['Opis'] ?? '',
            'slika' => $brand['Slika'] ?? '',
        ]);

    }

    /**
     * ## Novi brand
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function nova (string $kontroler = '', string $metoda = '', int $id = 0) {

        return sadrzaj()->format(Sadrzaj_Vrsta::HTMLP)->datoteka('brandovi/nova.html')->podatci([
            'id' => '0'
        ]);

    }

    /**
     * ## Spremi brand
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function spremi (string $kontroler = '', string $metoda = '', int $id = 0) {

        try {

            // model
            $brand = $this->model(Brand_Model::class);
            $brand->spremi($id);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'Poruka' => _('Postavke spremljene')
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

    /**
     * ## Izbrisi brand
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function izbrisi (string $kontroler = '', string $metoda = '', int $id = 0) {

        try {

            // model
            $brand = $this->model(Brand_Model::class);
            $brand->izbrisi($id);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'Poruka' => _('Postavke spremljene')
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

    /**
     * ### Spremi sliku branda
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function dodajSliku (string $kontroler = '', string $metoda = '', int $id = 0):Sadrzaj {

        try {

            // model
            $brand = $this->model(Brand_Model::class);
            $brand->dodajSliku($id);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'Poruka' => _('Uspješno spremljeno')
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

}