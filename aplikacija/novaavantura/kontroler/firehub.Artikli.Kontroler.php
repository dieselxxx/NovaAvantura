<?php declare(strict_types = 1);

/**
 * Artikli
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\NovaAvantura\Kontroler;

use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Model\Model;
use FireHub\Aplikacija\NovaAvantura\Model\Kategorije_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Artikli_Model;

/**
 * ### Artikli
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Artikli_Kontroler extends Master_Kontroler {

    protected Model $kategorije;
    protected Model $artikli;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->kategorije = $this->model(Kategorije_Model::class);

        $this->artikli = $this->model(Artikli_Model::class);

        parent::__construct();

    }

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (string $kontroler = '', string $kategorija = 'sve', int|string $trazi = 'svi artikli', string $poredaj = 'cijenafinal', string $poredaj_redoslijed = 'asc', int $stranica = 1):Sadrzaj {

        $trenutna_kategorija = $this->kategorije->kategorijaPoLinku($kategorija);

        // navigacija
        $limit = 1;
        $artikli = $this->model(Artikli_Model::class)->artikli(
            $trenutna_kategorija['ID'], ($stranica - 1) * $limit,
            $limit, $trazi, $poredaj, $poredaj_redoslijed
        );
        $navigacija = $this->model(Artikli_Model::class)->ukupnoRedakaHTML(
            $trenutna_kategorija['ID'], $trazi, $limit,
            '/artikli/'.$trenutna_kategorija['Link'].'/'.$trazi .'/'.$poredaj.'/'.$poredaj_redoslijed, $stranica
        );
        $navigacija_html = implode('', $navigacija);

        return sadrzaj()->datoteka('artikli.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => $trenutna_kategorija['Kategorija'],
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Artikli \\ '.$trenutna_kategorija['Kategorija'],
            'navigacija' => $navigacija_html
        ]));

    }

}