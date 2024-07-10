<?php declare(strict_types = 1);

/**
 * Artikli model
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
 * ### Artikli model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Artikli_Model extends Master_Model {

    /**
     * ### Konstruktor
     * @since 0.1.0.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ){

        parent::__construct();

    }

    /**
     * ### Dohvati artikle
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int|string $kategorija <p>
     * ID kategorije.
     * </p>
     * @param int $pomak <p>
     * Pomak od kojeg se limitiraju zapisi.
     * </p>
     * @param int $limit <p>
     * Broj redaka koje odabiremo.
     * </p>
     * @param int|string $trazi <p>
     * Traži artikl.
     * </p>
     * @param string $poredaj <p>
     * Poredaj rezultate artikala.
     * </p>
     * @param string $poredaj_redoslijed <p>
     * ASC ili DESC.
     * </p>
     *
     * @return array Niz artikala.
     */
    public function artikli (int|string $kategorija, int $pomak, int $limit, int|string $trazi, string $poredaj, string $poredaj_redoslijed):array {

        $filtar = match ($kategorija) {
            'izdvojeno' => "Izdvojeno = 1",
            'akcija' => Domena::sqlCijenaAkcija() . " > 0",
            'outlet' => Domena::sqlOutlet() . " = 1",
            'novo' => "Novo = 1",
            default => "kategorijeview.Link = '$kategorija'"
        };

        $poredaj = match ($poredaj) {
            'naziv' => 'Naziv',
            'starost' => 'DostupnoOd',
            default => 'CijenaIliAkcija'
        };

        $poredaj_redoslijed = match ($poredaj_redoslijed) {
            'desc' => 'desc',
            default => 'asc'
        };

        $rezultat = $this->bazaPodataka->tabela('artikliview')
            ->sirovi("
                    SELECT
                       artikliview.ID, Naziv, artikliview.Link, artikliview.Opis,
                       ".Domena::sqlCijena()." AS Cijena,
                       ".Domena::sqlCijenaAkcija()." AS CijenaAkcija,
                       IF(".Domena::sqlCijenaAkcija()." > 0, ".Domena::sqlCijenaAkcija().", ".Domena::sqlCijena().") AS CijenaIliAkcija,
                       GROUP_CONCAT(DISTINCT artiklikarakteristike.Velicina) AS Velicine,
                       brandovi.Brand,
                       (SELECT Slika FROM slikeartikal WHERE slikeartikal.ClanakID = artikliview.ID ORDER BY slikeartikal.Zadana DESC LIMIT 1) AS Slika,
                       ".(Domena::Hr() ? 'artikliview.GratisHr' : 'artikliview.GratisBa')." AS GratisID,
                       artikliview.Novo,
                       artikliview.Cijena30Dana".Domena::sqlTablica()." AS Cijena30Dana
                    FROM artikliview
                    LEFT JOIN artiklikarakteristike ON artiklikarakteristike.ArtikalID = artikliview.ID
                    LEFT JOIN brandovi ON brandovi.ID = artikliview.BrandID
                    LEFT JOIN kategorijeview ON kategorijeview.ID = artikliview.KategorijaID
                    WHERE Aktivan = 1 AND ".Domena::sqlTablica()." = 1 AND ".Domena::sqlCijena()." > 0 AND ".$filtar."
                    {$this->trazi($trazi)}
                    GROUP BY artikliview.ID
                    ORDER BY ".ucwords($poredaj)." $poredaj_redoslijed
                    LIMIT $pomak, $limit
                ")
            ->napravi()->niz() ?: [];

        foreach ($rezultat as $kljuc => $redak) {

            // najniža cijena 30 dana
            $rezultat[$kljuc]['Cijena30DanaHTML'] = Domena::Hr()
                ? 'najniža cijena u posljednih 30 dana: '.$redak['Cijena30Dana'] .' '.Domena::valuta()
                : '';

            // cijena
            if ($redak['CijenaAkcija'] > 0) {

                $rezultat[$kljuc]['CijenaFinal'] = $redak['CijenaAkcija'];
                $rezultat[$kljuc]['Popust'] = ($redak['Cijena'] - $redak['CijenaAkcija']) / (max($redak['Cijena'], 1)) * 100;

            } else if (Domena::blackFriday()) {

                $rezultat[$kljuc]['CijenaFinal'] = $redak['Cijena'] - ($redak['Cijena'] * Domena::blackFridayPopust());
                $rezultat[$kljuc]['Popust'] = Domena::blackFridayPopust() * 100;

            } else {

                $rezultat[$kljuc]['CijenaFinal'] = $redak['Cijena'];
                $rezultat[$kljuc]['Popust'] = 0;

            }

            // format cijena
            $rezultat[$kljuc]['CijenaFinal'] = number_format((float)$rezultat[$kljuc]['CijenaFinal'], 2, ',', '.');
            $rezultat[$kljuc]['Cijena'] = number_format((float)$rezultat[$kljuc]['Cijena'], 2, ',', '.');
            $rezultat[$kljuc]['CijenaAkcija'] = number_format((float)$rezultat[$kljuc]['CijenaAkcija'], 2, ',', '.');
            $rezultat[$kljuc]['Popust'] = number_format((float)$rezultat[$kljuc]['Popust'], 2, ',', '.');

            // cijena html
            if ($redak['CijenaAkcija'] > 0) {

                $rezultat[$kljuc]['CijenaHTML'] =
                    '<span class="prekrizi">'.$redak['Cijena'].' '.Domena::valuta().'</span>'
                    .$redak['CijenaAkcija'].' '.Domena::valuta();

            } else if (Domena::blackFriday()) {

                $rezultat[$kljuc]['CijenaHTML'] =
                    '<span class="prekrizi">'.$redak['Cijena'].' '.Domena::valuta().'</span>'
                    .$rezultat[$kljuc]['CijenaFinal'].' '.Domena::valuta();

            } else {

                $rezultat[$kljuc]['CijenaHTML'] = $redak['Cijena'].' '.Domena::valuta();

            }

            // popust html
            $rezultat[$kljuc]['PopustHTML'] = ($rezultat[$kljuc]['Popust'] !== '0,00')
                ? '<span class="popust">-'.$rezultat[$kljuc]['Popust'] .'%</span>'
                : '';

            // novo html
            $rezultat[$kljuc]['NovoHTML'] = $rezultat[$kljuc]['Novo']
                ? '<span class="novo">Novo</span>'
                : '';

        }

        return $rezultat;

    }

    /**
     * ### Ukupnjo pronađenih redaka
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int|string $kategorija <p>
     * ID kategorije.
     * </p>
     * @param int|string $trazi <p>
     * Traži artikl.
     * </p>
     *
     * @return int Broj pronađenih redaka.
     */
    public function ukupnoRedaka (int|string $kategorija, int|string $trazi) {

        $filtar = match ($kategorija) {
            'izdvojeno' => "Izdvojeno = 1",
            'akcija' => Domena::sqlCijenaAkcija() . " > 0",
            'outlet' => Domena::sqlOutlet() . " = 1",
            'novo' => "Novo = 1",
            default => "kategorijeview.Link = '$kategorija'"
        };

        return $this->bazaPodataka->tabela('artikliview')
            ->sirovi("
                SELECT
                    Naziv
                FROM artikliview
                LEFT JOIN artiklikarakteristike ON artiklikarakteristike.ArtikalID = artikliview.ID
                LEFT JOIN kategorijeview ON kategorijeview.ID = artikliview.KategorijaID
                WHERE Aktivan = 1 AND ".Domena::sqlTablica()." = 1 AND ".Domena::sqlCijena()." > 0 AND ".$filtar."
                {$this->trazi($trazi)}
                GROUP BY artikliview.ID
            ")
            ->napravi()->broj_zapisa();

    }

    /**
     * ### Navigacija HTML
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int|string $kategorija <p>
     * Kategorija artikla.
     * </p>
     * @param int|string $trazi <p>
     * Traži artikl.
     * </p>
     * @param int $limit <p>
     * Limit artikala.
     * </p>
     * @param string $url <p>
     * Trenutni URL.
     * </p>
     * @param int $broj_stranice <p>
     * Trenutnu broj stranice.
     * </p>
     * @param string $boja <p>
     * Boja gumbova.
     * </p>
     *
     * @return string[] Lista artikala.
     */
    public function ukupnoRedakaHTML (int|string $kategorija, int|string $trazi, int $limit, string $url = '/', int $broj_stranice = 1, string $boja = 'boja'):array {

        $broj_zapisa = $this->ukupnoRedaka($kategorija, $trazi);

        $pocetak_link_stranice = "";
        $link_stranice = "";
        $kraj_link_stranice = "";

        $ukupno_stranica = ceil($broj_zapisa / $limit);
        if (($broj_stranice - 2) < 1) {$x = 1;} else {$x = ($broj_stranice - 2);}
        if (($broj_stranice + 2) >= $ukupno_stranica) {$y = $ukupno_stranica;} else {$y = ($broj_stranice + 2);}

        if ($broj_stranice >= 2) {

            $prosla_stranica = $broj_stranice - 1;

            if ($url) {$url_prva_stranica = "href='{$url}/1'";} else {$url_prva_stranica = "";}
            if ($url) {$url_prosla_stranica = "href='{$url}/{$prosla_stranica}'";} else {$url_prosla_stranica = "";}

            $pocetak_link_stranice .= "<li><a class='gumb ikona' {$url_prva_stranica}><svg data-boja='{$boja}'><use xlink:href=\"/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#crta_lijevo_kraj\" /></svg></a></li>";
            $pocetak_link_stranice .= "<li><a class='gumb ikona' {$url_prosla_stranica}><svg data-boja='{$boja}'><use xlink:href=\"/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#crta_lijevo\" /></svg></a></li>";

        }

        for ($i = $x; $i <= $y; $i++) {

            if ($url) {$url_broj_stranice = "href='{$url}/{$i}'";} else {$url_broj_stranice = "";}

            if ($i == $broj_stranice) {

                $link_stranice .= "<li><a class='gumb' data-boja='{$boja}' {$url_broj_stranice}>{$i}</a></li>";

            }  else {

                $link_stranice .= "<li><a class='gumb' {$url_broj_stranice}>{$i}</a></li>";

            }

        }

        if ($broj_stranice < $ukupno_stranica) {

            $sljedeca_stranica = $broj_stranice + 1;

            if ($url) {$url_sljedeca_stranica = "href='{$url}/{$sljedeca_stranica}'";} else {$url_sljedeca_stranica = "";}
            if ($url) {$url_ukupno_stranica = "href='{$url}/{$ukupno_stranica}'";} else {$url_ukupno_stranica = "";}

            $kraj_link_stranice .= "<li><a class='gumb ikona' {$url_sljedeca_stranica}><svg data-boja='{$boja}'><use xlink:href=\"/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#crta_desno\" /></svg></a></li>";
            $kraj_link_stranice .= "<li><a class='gumb ikona' {$url_ukupno_stranica}><svg data-boja='{$boja}'><use xlink:href=\"/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#crta_desno_kraj\" /></svg></a></li>";

        }

        return [
            'pocetak' => $pocetak_link_stranice, 'stranice' => $link_stranice, 'kraj' => $kraj_link_stranice
        ];

    }

    /**
     * ### Traži artikl
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int|string $trazi <p>
     * Traži artikl.
     * </p>
     *
     * @return string Upit za traženje.
     */
    private function trazi (int|string $trazi):string {

        if ($trazi <> 'svi artikli') {

            $trazi = explode(' ', (string)$trazi);

            $trazi_array = '';
            foreach ($trazi as $stavka) {

                $trazi_array .= "
                    AND (
                        Naziv LIKE '%{$stavka}%'
                        OR Opis LIKE '%{$stavka}%'
                    )
                ";

            }

            return $trazi_array;

        }

        return '';

    }

}