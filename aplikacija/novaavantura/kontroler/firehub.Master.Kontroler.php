<?php declare(strict_types = 1);

/**
 * Master
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

use FireHub\Jezgra\Kontroler\Kontroler;
use FireHub\Aplikacija\NovaAvantura\Model\Gdpr_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Kategorije_Model;
use FireHub\Jezgra\Model\Model;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Domena;

/**
 * ### Master
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
abstract class Master_Kontroler extends Kontroler {

    protected Model $gdpr;
    protected Model $kategorije;

    /**
     * ### Konstruktor
     * @since 0.1.0.pre-alpha.M1
     */
    public function __construct () {

        $this->gdpr = $this->model(Gdpr_Model::class);
        $this->kategorije = $this->model(Kategorije_Model::class);

    }

    /**
     * ### Zadani podatci za parametere
     * @since 0.1.0.pre-alpha.M1
     *
     * @return array
     */
    protected function zadaniPodatci ():array {

        return [
            'gdpr' => $this->gdpr->html(),
            'predlozak_opis' => Domena::opis(),
            'adresa' => Domena::adresa(),
            'telefon' => Domena::telefon(),
            'email' => Domena::email(),
            'kategorije_meni' => $this->kategorijeMeni(),
            'kategorije_podnozje_meni' => $this->kategorijePodnozjeTreeHTML($this->kategorije->kategorije())
        ];

    }

    /**
     * ### HTML meni za kategorije
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    private function kategorijeMeni ():string {

        return '
        <a href="javascript:void(0);" class="menu-link menu-bar-link" aria-haspopup="true">Artikli</a>
        <ul class="mega-meni mega-meni--multiLevel">
            '.$this->kategorijeTreeHTML($this->kategorijeTree($this->kategorije->kategorije())).'
        </ul>
        ';

    }

    /**
     * ### Tree meni za kategorije
     * @since 0.1.0.pre-alpha.M1
     *
     * @return array
     */
    private function kategorijeTree (array $lista, $roditelj = 0):array {

        $rezultat = [];

        foreach ($lista as $kategorija) {

            if ($kategorija['Roditelj'] == $roditelj) {

                $children = $this->kategorijeTree($lista, $kategorija['ID']);

                if ($children) {

                    $kategorija['Djeca'] = $children;

                }

                $rezultat[] = $kategorija;

            }

        }

        return $rezultat;

    }

    /**
     * ### HTML tree meni za kategorije
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    private function kategorijeTreeHTML (array $lista, $level = 0):string {

        $rezultat = '';

        foreach ($lista as $kategorija)  {

            $rezultat .= '<li>';

            if (!isset($kategorija['Djeca'])) {

                $rezultat .= '<a href="'.$kategorija['Link'].'" class="menu-link mega-meni-link">'.$kategorija['Kategorija'].'</a>';

            } else if (count($kategorija['Djeca']))  {

                $rezultat .= '<a href="javascript:void(0);" class="menu-link mega-meni-link" aria-haspopup="true">'.$kategorija['Kategorija'].'</a>';

                $rezultat .= '<ul class="menu menu-list">';

                $rezultat .= $this->kategorijeTreeHTML($kategorija['Djeca'], $level + 1);

                $rezultat .= '</ul>';

            }

            $rezultat .= '</li>';

        }

        return $rezultat;

    }

    /**
     * ### HTML tree meni za kategorije
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    private function kategorijePodnozjeTreeHTML (array $lista):string {

        $rezultat = '';

        foreach ($lista as $kategorija)  {

            if ($kategorija['Roditelj'] == 0)
                $rezultat .= '<li><a href="'.$kategorija['Link'].'"><span>'.$kategorija['Kategorija'].'</span></a></li>';

        }

        return $rezultat;

    }

}