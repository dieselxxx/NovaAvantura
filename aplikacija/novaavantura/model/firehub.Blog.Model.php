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

        $blogovi = $this->bazaPodataka->tabela('blog')
            ->odaberi(['ID', 'Naslov', 'Opis', 'Datum', 'Slika'])
            ->poredaj('Datum', 'desc')
            ->napravi()
            ->niz() ?: [];

        foreach ($blogovi as $key => $blog) {

            $blogovi[$key]['Datum'] = (new DateTime($blog['Datum']))->format('l jS \o\f F Y');
        }

        return $blogovi;

    }

    /**
     * ### Dohvati blog
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $id <p>
     * ID bloga.
     * </p>
     *
     * @return array Blog.
     */
    public function blog (int $id):array|false {

        $blog = $this->bazaPodataka->tabela('blog')
            ->odaberi(['ID', 'Naslov', 'Opis', 'Datum', 'Slika'])
            ->gdje('ID', '=', $id)
            ->napravi()->redak();

        $blog['Datum'] = (new DateTime($blog['Datum']))->format('l jS \o\f F Y');

        return $blog;

    }

}