<?php declare(strict_types = 1);

/**
 * Kategorije model
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

/**
 * ### Kategorije model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Kategorije_Model extends Master_Model {

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
     * ### Sve kategorije
     * @since 0.1.1.pre-alpha.M1
     *
     * @return array Niz kategorija.
     */
    public function kategorije ():array {

        return $this->bazaPodataka->tabela('kategorijeview')
            ->sirovi("
                SELECT 
                    kategorijeview.ID, kategorijeview.Kategorija, kategorijeview.Link, kategorijeview.Slika, kategorijeview.Roditelj
                FROM kategorijeview
            ")->napravi()->niz();

    }

    /**
     * ### Dohvati kategoriju po linku
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string $link <p>
     * Link kategorije.
     * </p>
     *
     * @return array Kategorija.
     */
    public function kategorijaPoLinku (string $link):array {

        return match ($link) {
            'izdvojeno', 'akcija', 'outlet', 'novo', 'sve kategorije' => [
                'ID' => 0, 'Kategorija' => ucfirst($link), 'Opis' => '', 'Slika' => '', 'Link' => $link
            ],
            default => $this->bazaPodataka->tabela('kategorijeview')
                ->odaberi(['ID', 'Kategorija', 'Opis', 'Slika', 'Link'])
                ->gdje('Link', '=', $link)
                ->napravi()->redak() ?: [
                        'ID' => 0, 'Kategorija' => 'Kategorija ne postoji', 'Opis' => '', 'Slika' => '', 'Link' => ''
                    ]
        };

    }

    /**
     * ### Dohvati kategoriju po artiklu
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id <p>
     * ID artikla.
     * </p>
     *
     * @return array Kategorija.
     */
    public function kategorijaPoArtiklu (int $id):array {

        $kategorija = $this->bazaPodataka->tabela('artikliview')
            ->odaberi(['KategorijaID'])
            ->gdje('ID', '=', $id)
            ->napravi()->redak()['KategorijaID'];

        return $this->bazaPodataka->tabela('kategorijeview')
            ->odaberi(['ID', 'Kategorija', 'Opis', 'Slika', 'Link'])
            ->gdje('ID', '=', $kategorija)
            ->napravi()->redak();

    }

    /**
     * ### Dohvati roditelje kategorije
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id <p>
     * ID kategorije.
     * </p>
     * @param array $rezultat <p>
     * Rezultati.
     * </p>
     *
     * @return array Kategorija.
     */
    public function kategorijeRoditelji (int $id, array $rezultat = []):array {

        $roditelj = $this->bazaPodataka->tabela('kategorijeview')
            ->odaberi(['Roditelj', 'Kategorija', 'Link'])
            ->gdje('ID', '=', $id)
            ->napravi()->redak();

        $rezultat[] = $roditelj;

        if (!$roditelj) return [];

        if ($roditelj['Roditelj'] === '0') return array_reverse($rezultat);

        return $this->kategorijeRoditelji((int)$roditelj['Roditelj'], $rezultat);

    }

}