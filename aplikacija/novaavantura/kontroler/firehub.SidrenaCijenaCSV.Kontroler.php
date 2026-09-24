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

use FireHub\Aplikacija\NovaAvantura\Model\SidrenaCijenaCSV_Model;
use FireHub\Jezgra\Greske\Greska;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Sadrzaj\Enumeratori\Vrsta as Sadrzaj_Vrsta;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;

/**
 * ### Sidrena Cijena CSV
 * @since 0.1.2.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class SidrenaCijenaCSV_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.2.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function index ():Sadrzaj {

        try {

            // model
            $model = $this->model(SidrenaCijenaCSV_Model::class);
            $artikli = $model->artikli();

            $putanja = FIREHUB_ROOT.konfiguracija('sustav.putanje.web')
                .'novaavantura'.RAZDJELNIK_MAPE
                .'resursi'.RAZDJELNIK_MAPE
                .'cjenik'.RAZDJELNIK_MAPE;

            if (!is_dir($putanja) && !mkdir($putanja, 0775, true) && !is_dir($putanja)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $putanja));
            }

            // Izbriši CSV datoteke starije od 30 dana
            $granica = time() - (30 * 24 * 60 * 60);

            foreach (glob($putanja.'*.csv') ?: [] as $datoteka) {

                if (is_file($datoteka) && filemtime($datoteka) < $granica) {
                    unlink($datoteka);
                }

            }

            // Kreiraj današnji CSV
            $datoteka = $putanja.date('Y-m-d').'.csv';

            $file = fopen($datoteka, 'wb');

            // UTF-8 BOM za Excel
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['Naziv', 'Cijena'], ';');

            foreach ($artikli as $artikal) {
                fputcsv($file, [
                    $artikal['Naziv'],
                    $artikal['Cijena']
                ], ';');
            }

            fclose($file);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'artikli' => $artikli,
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

}