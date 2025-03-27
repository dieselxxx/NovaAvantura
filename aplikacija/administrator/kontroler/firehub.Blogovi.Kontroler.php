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

use FireHub\Aplikacija\Administrator\Model\Blog_Model;
use FireHub\Aplikacija\Administrator\Model\Blogovi_Model;
use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Sadrzaj\Enumeratori\Vrsta as Sadrzaj_Vrsta;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;

/**
 * ### Blog
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Blogovi_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        return sadrzaj()->datoteka('blogovi/lista.html')->podatci([]);

    }

    /**
     * ## Lista blogova
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function lista (string $kontroler = '', string $metoda = '', int $broj_stranice = 1, string $poredaj = 'Datum', string $redoslijed = 'desc'):Sadrzaj {

        try {

            // model
            $blogovi = $this->model(Blogovi_Model::class);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'Blogovi' => $blogovi->lista($broj_stranice, $poredaj, $redoslijed),
                'Zaglavlje' => $blogovi->IspisiZaglavlje(),
                'Navigacija' => $blogovi->IspisiNavigaciju()
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

    /**
     * ## Uredi blogove
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function uredi (string $kontroler = '', string $metoda = '', int $id = 0) {

        $blog_model = $this->model(Blog_Model::class);
        $blog = $blog_model->blog($id);

        return sadrzaj()->format(Sadrzaj_Vrsta::HTMLP)->datoteka('blogovi/uredi.html')->podatci([
            'id' => $blog['ID'],
            'naslov' => $blog['Naslov'],
            'datum' => $blog['Datum'],
            'opis' => $blog['Opis'] ?? '',
            'slika' => $blog['Slika'] ?? ''
        ]);

    }

    /**
     * ## Novi blog
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function novi (string $kontroler = '', string $metoda = '', int $id = 0) {

        return sadrzaj()->format(Sadrzaj_Vrsta::HTMLP)->datoteka('blogovi/novi.html')->podatci([
            'id' => '0'
        ]);

    }

    /**
     * ## Spremi blog
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function spremi (string $kontroler = '', string $metoda = '', int $id = 0) {

        try {

            // model
            $blog = $this->model(Blog_Model::class);
            $blog->spremi($id);

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
     * ## Izbrisi blog
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function izbrisi (string $kontroler = '', string $metoda = '', int $id = 0) {

        try {

            // model
            $blog = $this->model(Blog_Model::class);
            $blog->izbrisi($id);

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
     * ### Spremi sliku bloga
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function dodajSliku (string $kontroler = '', string $metoda = '', int $id = 0):Sadrzaj {

        try {

            // model
            $blog = $this->model(Blog_Model::class);
            $blog->dodajSliku($id);

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