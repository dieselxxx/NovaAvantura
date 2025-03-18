<?php declare(strict_types = 1);

/**
 * Blog
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

use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Aplikacija\NovaAvantura\Model\Blog_Model;

/**
 * ### Blog
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Blog_Kontroler extends Master_Kontroler {

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (string $kontroler = '', int $id = 1):Sadrzaj {

        $blog = $this->model(Blog_Model::class)->blog($id);

        return sadrzaj()->datoteka('blog.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => $blog['Naslov'],
            'vi_ste_ovdje' => '<a href="/">Nova Avantura</a> \\ '.$blog['Naslov'],
            'naslov' => $blog['Naslov'],
            'datum' => $blog['Datum'],
            'opis' => $blog['Opis'] ?? '',
            'slika' => '<img src="/slika/blog/'.$blog['Slika'].'" />'
        ]));

    }

}