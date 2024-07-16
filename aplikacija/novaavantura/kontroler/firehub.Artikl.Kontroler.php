<?php declare(strict_types = 1);

/**
 * Artikl
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
use FireHub\Aplikacija\NovaAvantura\Model\Artikl_Model;

/**
 * ### Artikl
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Artikl_Kontroler extends Master_Kontroler {

    protected Model $artikl;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->artikl = $this->model(Artikl_Model::class);

        parent::__construct();

    }

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (string $kontroler = '', string $artikl = ''):Sadrzaj {

        $trenutni_artikl = $this->artikl->artikl($artikl);

        // slike
        $artikl_slike = $this->artikl->slike($trenutni_artikl['ID']);
        $artikl_slike_html = '';
        foreach ($artikl_slike as $slike) {

            $artikl_slike_html .= '
                <div>
                    <a data-vrsta="slika" href="/slika/velikaslika/'.$slike['Slika'].'"><img src="/slika/malaslika/'.$slike['Slika'].'" alt=""></a>
                </div>';

        }

        if ($trenutni_artikl['ID'] === 0 || empty($this->artikl->zaliha)) {

            return sadrzaj()->datoteka('artikl_ne_postoji.html')->podatci(array_merge($this->zadaniPodatci(), [
                'predlozak_naslov' => 'Artikal ne postoji',
                'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Artikl \\ Artikal ne postoji'
            ]));

        }

        return sadrzaj()->datoteka('artikl.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => $trenutni_artikl['Naziv'],
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ Artikl \\ '.$trenutni_artikl['Naziv'],
            'artikl_naziv' => $trenutni_artikl['Naziv'],
            'artikl_id' => $trenutni_artikl['ID'],
            'artikl_slika' => ''.$trenutni_artikl['Slika'],
            'artikl_novo' => $trenutni_artikl['NovoHTML'],
            'artikl_popust' => $trenutni_artikl['PopustHTML'],
            'artikl_slike' => $artikl_slike_html,
            'artikl_brand' => $trenutni_artikl['Brand'] ? '<span>Brand: </span>'.$trenutni_artikl['Brand'] : '',
            'artikl_cijena' => $trenutni_artikl['CijenaHTML'],
            'artikl_cijena_30_dana' => $trenutni_artikl['Cijena30DanaHTML'],
            'artikl_opis' => $trenutni_artikl['Opis'] ? '<h5>Dodatne informacije: </h5><span>'.$trenutni_artikl['Opis'] .'</span>' : '',
            ///'artikl_zaliha' => $artikl_zaliha_html,
        ]));

    }

}