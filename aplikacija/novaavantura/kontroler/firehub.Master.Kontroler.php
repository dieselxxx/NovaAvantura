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

    /**
     * ### Konstruktor
     * @since 0.1.0.pre-alpha.M1
     */
    public function __construct () {

        $this->gdpr = $this->model(Gdpr_Model::class);

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
        ];

    }

}