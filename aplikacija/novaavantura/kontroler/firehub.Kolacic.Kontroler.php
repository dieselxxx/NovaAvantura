<?php declare(strict_types = 1);

/**
 * Kolacic
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

use FireHub\Aplikacija\NovaAvantura\Model\Gdpr_Model;
use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Sadrzaj\Enumeratori\Vrsta as Sadrzaj_Vrsta;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;

/**
 * ### Kolacic
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Kolacic_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function index (string $kolacic = ''):Sadrzaj {

        try {

            // model
            $gdpr = $this->model(Gdpr_Model::class);
            $gdpr->prihvati();

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da'
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

    /**
     * ## Osobni podatci
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function osobniPodatci ():Sadrzaj {

        return sadrzaj()->datoteka('osobni_podatci.html')->podatci(array_merge($this->zadaniPodatci(), [
            'gdpr' =>'',
            'predlozak_naslov' => 'Osobni podatci',
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Osobni podatci'
        ]));

    }

}