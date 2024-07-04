<?php declare(strict_types = 1);

/**
 * Artikli
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

use FireHub\Aplikacija\Administrator\Model\Artikl_Model;
use FireHub\Aplikacija\Administrator\Model\Kategorije_Model;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\Administrator\Model\Artikli_Model;
use FireHub\Jezgra\HTTP\Enumeratori\Vrsta;
use FireHub\Jezgra\Sadrzaj\Enumeratori\Vrsta as Sadrzaj_Vrsta;
use FireHub\Jezgra\HTTP\Atributi\Zaglavlja;
use FireHub\Jezgra\Greske\Greska;

/**
 * ### Artikli
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Artikli_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (string $kontroler = '', string|int $kategorija = ''):Sadrzaj {

        return sadrzaj()->datoteka('artikli/lista.html')->podatci([
            'kategorija' => ''.$kategorija
        ]);

    }

    /**
     * ## Lista artikala
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function lista (string $kontroler = '', string $metoda = '', int $broj_stranice = 1, string $poredaj = 'Naziv', string $redoslijed = 'asc', string|int $kategorija = ''):Sadrzaj {

        try {

            // model
            $artikli = $this->model(Artikli_Model::class);

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'da',
                'Artikli' => $artikli->lista($broj_stranice, $poredaj, $redoslijed, $kategorija),
                'Zaglavlje' => $artikli->IspisiZaglavlje(),
                'Navigacija' => $artikli->IspisiNavigaciju()
            ]);

        } catch (Greska $greska) {

            return sadrzaj()->format(Sadrzaj_Vrsta::JSON)->podatci([
                'Validacija' => 'ne',
                'Poruka' => $greska->getMessage()
            ]);

        }

    }

    /**
     * ## Uredi šifre artikla
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function artiklsifre (string $kontroler = '', string $metoda = '', int $id = 0):Sadrzaj {

        $artikl_model = $this->model(Artikl_Model::class);
        $artiklKarakteristike = $artikl_model->artiklKarakteristike($id);

        $artiklKarakteristikehtml = '';
        foreach ($artiklKarakteristike as $karakteristika) {

            $artiklKarakteristikehtml .= '
                <tr>
                    <td>
                        <label data-boja="boja" class="unos">
                            <input type="text" name="zaliha['.$karakteristika['ID'].']" value="'.$karakteristika['artiklikarakteristikeSifra'].'" maxlength="50" autocomplete="off">
                            <span class="naslov">
                                <span>Šifra</span>
                            </span>
                            <span class="granica"></span>
                            <svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#cijena"></use></svg>
                            <span class="upozorenje"></span>
                        </label>
                    </td>
                    <td>
                        <label data-boja="boja" class="unos">
                            <input type="text" name="velicina['.$karakteristika['ID'].']" value="'.$karakteristika['Velicina'].'" maxlength="50" autocomplete="off">
                            <span class="naslov">
                                <span>Veličina</span>
                            </span>
                            <span class="granica"></span>
                            <svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#cijena"></use></svg>
                            <span class="upozorenje"></span>
                        </label>
                    </td>
                    <td>
                        <button type="button" class="ikona" onclick="$_ArtiklSifreIzbrisi(this, '.$karakteristika['ID'].', '.$id.')"><svg><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#izbrisi"></use></svg><span></span></button>
                    </td>
                </tr>
            ';

        }

        return sadrzaj()->format(Sadrzaj_Vrsta::HTMLP)->datoteka('artikli/uredisifre.html')->podatci([
            'id' => ''.$id.'',
            'artiklKarakteristikehtml' => $artiklKarakteristikehtml
        ]);

    }

    /**
     * ### Spremi šifre artikla
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function artiklsifrespremi (string $kontroler = '', string $metoda = '', int $id = 0):Sadrzaj {

        try {

            // model
            $artikl = $this->model(Artikl_Model::class);
            $artikl->artiklsifrespremi($id);

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
     * ### Spremi šifre artikla
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj
     */
    #[Zaglavlja(vrsta: Vrsta::JSON)]
    public function artiklsifreizbrisi (string $kontroler = '', string $metoda = '', int $id = 0):Sadrzaj {

        try {

            // model
            $artikl = $this->model(Artikl_Model::class);
            $artikl->artiklsifreizbrisi($id);

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

}