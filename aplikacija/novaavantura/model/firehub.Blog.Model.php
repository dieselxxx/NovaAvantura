<?php declare(strict_types = 1);

/**
 * Blog model
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

use DateTime;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Domena;
use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;

/**
 * ### Blog model
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Blog_Model extends Master_Model {

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
     * ### Blogovi
     * @since 0.1.0.pre-alpha.M1
     *
     * @return array
     */
    public function blogovi ():array {

        $blogovi = $this->bazaPodataka->tabela('blogview')
            ->odaberi(['ID', 'Naslov', 'Opis', 'Datum', 'Slika', 'Link'])
            ->poredaj('Datum', 'desc')
            ->napravi()
            ->niz() ?: [];

        foreach ($blogovi as $key => $blog) {

            $blogovi[$key]['Datum'] = (
                new \IntlDateFormatter('hr_HR', \IntlDateFormatter::FULL, \IntlDateFormatter::SHORT)
            )->format(new DateTime($blog['Datum']));

        }

        return $blogovi;

    }

    /**
     * ### Dohvati blog
     * @since 0.1.0.pre-alpha.M1
     *
     * @param string $link <p>
     * Link bloga.
     * </p>
     *
     * @return array Blog.
     */
    public function blog (string $link = ''):array|false {

        if ($link == '') {

            $blog = $this->bazaPodataka->tabela('blogview')
                ->odaberi(['ID', 'Naslov', 'Opis', 'Datum', 'Slika', 'Link'])
                ->poredaj('Datum', 'desc')
                ->limit(0, 1)
                ->napravi()->redak();

        } else {

            $blog = $this->bazaPodataka->tabela('blogview')
                ->odaberi(['ID', 'Naslov', 'Opis', 'Datum', 'Slika', 'Link'])
                ->gdje('Link', '=', $link)
                ->napravi()->redak();

        }

        $blog['Datum'] = (
        new \IntlDateFormatter('hr_HR', \IntlDateFormatter::FULL, \IntlDateFormatter::SHORT)
        )->format(new DateTime($blog['Datum']));

        return $blog;

    }

}