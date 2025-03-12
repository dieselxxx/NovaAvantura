<?php declare(strict_types = 1);

/**
 * Slika
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2024 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\NovaAvantura\Kontroler;

use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Komponente\Slika\Slika;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\HTTP\Enumeratori\Predmemorija;
use FireHub\Jezgra\Komponente\Slika\Slika_Interface;
use FireHub\Jezgra\Kontejner\Greske\Kontejner_Greska;

/**
 * ### Slika
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Slika_Kontroler extends Master_Kontroler {

    /**
     * ### index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index ():Sadrzaj {

        return sadrzaj();

    }

    /**
     * ### Mala slika
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string $kontroler [optional] <p>
     * Trenutni kontroler.
     * </p>
     * @param string $metoda [optional] <p>
     * Trenutna metoda.
     * </p>
     * @param string $slika [optional] <p>
     * Trenutna slika.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Slike.
     *
     * @return Slika_Interface Objekt slike.
     */
    #[Zaglavlja(vrsta: Vrsta::AVIF, predmemorija: [Predmemorija::JAVNO])]
    public function malaSlika (string $kontroler = '', string $metoda = '', string $slika = ''):Slika_Interface {

        return (new Slika())->slika(FIREHUB_ROOT.'web'.RAZDJELNIK_MAPE.'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'artikli'.RAZDJELNIK_MAPE.$slika)
            ->kvaliteta(100)
            ->dimenzije(445, 350)
            ->napravi();

    }

    /**
     * ### Velika slika
     * @since 0.1.2.pre-alpha.M1
     *
     * @param string $kontroler [optional] <p>
     * Trenutni kontroler.
     * </p>
     * @param string $metoda [optional] <p>
     * Trenutna metoda.
     * </p>
     * @param string $slika [optional] <p>
     * Trenutna slika.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Slike.
     *
     * @return Slika_Interface Objekt slike.
     */
    #[Zaglavlja(vrsta: Vrsta::AVIF, predmemorija: [Predmemorija::JAVNO])]
    public function velikaSlika (string $kontroler = '', string $metoda = '', string $slika = ''):Slika_Interface {

        return (new Slika())
            ->slika(FIREHUB_ROOT.'web'.RAZDJELNIK_MAPE.'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'artikli'.RAZDJELNIK_MAPE.$slika)
            ->kvaliteta(100)
            ->dimenzije(700, 550)
            ->napravi();

    }

    /**
     * ### Baner slika
     * @since 0.1.2.pre-alpha.M1
     *
     * @param string $kontroler [optional] <p>
     * Trenutni kontroler.
     * </p>
     * @param string $metoda [optional] <p>
     * Trenutna metoda.
     * </p>
     * @param string $slika [optional] <p>
     * Trenutna slika.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Slike.
     *
     * @return Slika_Interface Objekt slike.
     */
    #[Zaglavlja(vrsta: Vrsta::AVIF, predmemorija: [Predmemorija::JAVNO])]
    public function baner (string $kontroler = '', string $metoda = '', string $slika = '', int $visina = 550, int $sirina = 1920):Slika_Interface {

        return (new Slika())->slika(FIREHUB_ROOT.'web'.RAZDJELNIK_MAPE.'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE.'grafika'.RAZDJELNIK_MAPE.'baneri'.RAZDJELNIK_MAPE.$slika)
            ->kvaliteta(100)
            ->dimenzije($visina, $sirina)
            ->napravi();

    }

    /**
     * ### Kategorija
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string $kontroler [optional] <p>
     * Trenutni kontroler.
     * </p>
     * @param string $metoda [optional] <p>
     * Trenutna metoda.
     * </p>
     * @param string $slika [optional] <p>
     * Trenutna slika.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Slike.
     *
     * @return Slika_Interface Objekt slike.
     */
    #[Zaglavlja(vrsta: Vrsta::AVIF, predmemorija: [Predmemorija::JAVNO])]
    public function kategorija (string $kontroler = '', string $metoda = '', string $slika = '', int $visina = 300, int $sirina = 400):Slika_Interface {

        return (new Slika())->slika(FIREHUB_ROOT.'web'.RAZDJELNIK_MAPE.'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE .'grafika'.RAZDJELNIK_MAPE.'kategorije'.RAZDJELNIK_MAPE.$slika)
            ->dimenzije($visina, $sirina)
            ->kvaliteta(9)
            ->vrsta(\FireHub\Jezgra\Komponente\Slika\Enumeratori\Vrsta::PNG)
            ->napravi();

    }

    /**
     * ### Brand
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string $kontroler [optional] <p>
     * Trenutni kontroler.
     * </p>
     * @param string $metoda [optional] <p>
     * Trenutna metoda.
     * </p>
     * @param string $slika [optional] <p>
     * Trenutna slika.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Slike.
     *
     * @return Slika_Interface Objekt slike.
     */
    #[Zaglavlja(vrsta: Vrsta::AVIF, predmemorija: [Predmemorija::JAVNO])]
    public function brand (string $kontroler = '', string $metoda = '', string $slika = '', int $visina = 50, int $sirina = 50):Slika_Interface {

        return (new Slika())->slika(FIREHUB_ROOT.'web'.RAZDJELNIK_MAPE.'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE .'grafika'.RAZDJELNIK_MAPE.'brandovi'.RAZDJELNIK_MAPE.$slika)
            ->dimenzije($visina, $sirina)
            ->kvaliteta(9)
            ->vrsta(\FireHub\Jezgra\Komponente\Slika\Enumeratori\Vrsta::PNG)
            ->napravi();

    }

    /**
     * ### Blog
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string $kontroler [optional] <p>
     * Trenutni kontroler.
     * </p>
     * @param string $metoda [optional] <p>
     * Trenutna metoda.
     * </p>
     * @param string $slika [optional] <p>
     * Trenutna slika.
     * </p>
     *
     * @throws Kontejner_Greska Ukoliko se ne može spremiti instanca Slike.
     *
     * @return Slika_Interface Objekt slike.
     */
    #[Zaglavlja(vrsta: Vrsta::AVIF, predmemorija: [Predmemorija::JAVNO])]
    public function blog (string $kontroler = '', string $metoda = '', string $slika = '', int $visina = 800, int $sirina = 600):Slika_Interface {

        return (new Slika())->slika(FIREHUB_ROOT.'web'.RAZDJELNIK_MAPE.'novaavantura'.RAZDJELNIK_MAPE.'resursi'.RAZDJELNIK_MAPE .'grafika'.RAZDJELNIK_MAPE.'blog'.RAZDJELNIK_MAPE.$slika)
            ->dimenzije($visina, $sirina)
            ->kvaliteta(9)
            ->vrsta(\FireHub\Jezgra\Komponente\Slika\Enumeratori\Vrsta::PNG)
            ->napravi();

    }

}