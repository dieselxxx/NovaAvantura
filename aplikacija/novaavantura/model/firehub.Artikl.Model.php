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

namespace FireHub\Aplikacija\NovaAvantura\Model;

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Domena;

/**
 * ### Artikl model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Artikl_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.0.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ){parent::__construct();}

    /**
     * ### Dohvati artikl
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string $link <p>
     * Link artikla.
     * </p>
     *
     * @return array Artikl.
     */
    public function artikl (string $link) {

        $artikl = $this->bazaPodataka->tabela('artikliview')
            ->sirovi("
                SELECT
                    artikliview.ID, artikliview.Naziv, artikliview.Opis, artikliview.".Domena::sqlCijena()." AS Cijena, artikliview.".Domena::sqlCijenaAkcija()." AS CijenaAkcija, artikliview.Novo,
                    kategorijeview.Kategorija, kategorijeview.Link as KategorijaLink, slikeartikal.Slika, artikliview.Link,
                    brandovi.Brand,
                    ".(Domena::Hr() ? 'artikliview.GratisHr' : 'artikliview.GratisBa')." AS GratisID, gratis.Naziv AS GratisNaziv, gratisslika.Slika AS GratisSlika, gratis.Link AS GratisLink,
                    artikliview.Cijena30Dana".Domena::sqlTablica()." AS Cijena30Dana
                FROM artikliview
                LEFT JOIN kategorijeview ON kategorijeview.ID = artikliview.KategorijaID
                LEFT JOIN slikeartikal ON slikeartikal.ClanakID = artikliview.ID
                LEFT JOIN artikliview gratis ON gratis.ID = ".(Domena::Hr() ? 'artikliview.GratisHr' : 'artikliview.GratisBa')." AND gratis.Aktivan = 1 AND ".(Domena::Hr() ? 'gratis.Hr' : 'gratis.Ba')." = 1
                LEFT JOIN slikeartikal gratisslika ON gratisslika.ClanakID = gratis.ID
                LEFT JOIN brandovi ON brandovi.ID = artikliview.BrandID
                WHERE artikliview.Link = '$link' AND artikliview.Aktivan = 1 AND artikliview.".Domena::sqlTablica()." = 1
                ORDER BY slikeartikal.Zadana DESC
                LIMIT 1
            ")
            ->napravi()->redak();

        if (!$artikl) {

            return [
                'ID' => 0,
                'Naziv' => 'Artikl ne postoji',
                'Kategorija' => 'sve kategorije'
            ];

        }

        // najniža cijena 30 dana
        $artikl['Cijena30DanaHTML'] = Domena::Hr()
            ? 'najniža cijena u posljednih 30 dana: '.$artikl['Cijena30Dana'] .' '.Domena::valuta()
            : '';

        // cijena
        if ($artikl['CijenaAkcija'] > 0) {

            $artikl['CijenaFinal'] = $artikl['CijenaAkcija'];
            $artikl['Popust'] = ($artikl['Cijena'] - $artikl['CijenaAkcija']) / (max($artikl['Cijena'], 1)) * 100;

        } else if (Domena::blackFriday()) {

            $artikl['CijenaFinal'] = $artikl['Cijena'] - ($artikl['Cijena'] * Domena::blackFridayPopust());
            $artikl['Popust'] = Domena::blackFridayPopust() * 100;

        } else {

            $artikl['CijenaFinal'] = $artikl['Cijena'];
            $artikl['Popust'] = 0;

        }

        $artikl['CijenaFinalHTML'] = number_format((float)$artikl['CijenaFinal'], 2, ',', '.');
        $artikl['CijenaNormalnaHTML'] = number_format((float)$artikl['Cijena'], 2, ',', '.');
        $artikl['CijenaAkcijaHTML'] = number_format((float)$artikl['CijenaAkcija'], 2, ',', '.');
        $artikl['Popust'] = number_format((float)$artikl['Popust'], 2, ',', '.');

        // cijena html
        if ($artikl['CijenaAkcija'] > 0) {

            $artikl['CijenaHTML'] =
                '<span class="prekrizi">'.$artikl['CijenaNormalnaHTML'].' '.Domena::valuta().'</span>'
                .$artikl['CijenaAkcijaHTML'].' '.Domena::valuta();

        } else if (Domena::blackFriday()) {

            $artikl['CijenaHTML'] =
                '<span class="prekrizi">'.$artikl['CijenaNormalnaHTML'].' '.Domena::valuta().'</span>'
                .$artikl['CijenaFinalHTML'].' '.Domena::valuta();

        } else {

            $artikl['CijenaHTML'] = $artikl['CijenaNormalnaHTML'].' '.Domena::valuta();

        }

        // popust html
        $artikl['PopustHTML'] = ($artikl['Popust'] !== '0,00')
            ? '<span class="popust">-'.$artikl['Popust'] .'%</span>'
            : '';

        // novo html
        $artikl['NovoHTML'] = $artikl['Novo']
            ? '<span class="novo">Novo</span>'
            : '';

        return $artikl;

    }

    /**
     * ### Dohvati slike artikla
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string|int $artiklID <p>
     * ID artikla.
     * </p>
     *
     * @return array Artikl.
     */
    public function slike (string|int $artiklID):array {

        $slike = $this->bazaPodataka->tabela('slikeartikal')
            ->odaberi(['Slika'])
            ->gdje('ClanakID', '=', $artiklID)
            ->poredaj('Zadana', 'DESC')->napravi();

        return $slike->niz() ?: [];

    }

    /**
     * ### Dohvati karakteristike artikla
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string|int $artiklID <p>
     * ID artikla.
     * </p>
     *
     * @return array Artikl.
     */
    public function zaliha (string|int $artiklID):array {

        if (Domena::Hr()) {

            $karakteristike = $this->bazaPodataka->tabela('artiklikarakteristike')
                ->sirovi("
                SELECT
                    SUM(StanjeSkladiste) AS StanjeSkladiste, IF(SUM(StanjeSkladiste) > 0, TRUE, FALSE) AS StanjeSkladisteTF,
                    artiklikarakteristike.Sifra AS artiklikarakteristikeSifra, Velicina
                FROM artiklikarakteristike
                LEFT JOIN stanjeskladista ON stanjeskladista.Sifra = artiklikarakteristike.Sifra
                WHERE ArtikalID = $artiklID
                AND (SkladisteID = 3)
                GROUP BY Velicina
                ORDER BY artiklikarakteristike.ID
            ")
                ->napravi();

        } else {

            $karakteristike = $this->bazaPodataka->tabela('artiklikarakteristike')
                ->sirovi("
                SELECT
                    SUM(StanjeSkladiste) AS StanjeSkladiste, IF(SUM(StanjeSkladiste) > 0, TRUE, FALSE) AS StanjeSkladisteTF,
                    artiklikarakteristike.Sifra AS artiklikarakteristikeSifra, Velicina
                FROM artiklikarakteristike
                LEFT JOIN stanjeskladista ON stanjeskladista.Sifra = artiklikarakteristike.Sifra
                WHERE ArtikalID = $artiklID
                AND (SkladisteID = 1 OR SkladisteID = 2)
                GROUP BY Velicina
                ORDER BY artiklikarakteristike.ID
            ")
                ->napravi();

        }

        return $karakteristike->niz() ?: [];

    }

}