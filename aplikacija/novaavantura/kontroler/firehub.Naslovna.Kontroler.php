<?php declare(strict_types = 1);

/**
 * Naslovna
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

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Jezgra\Sadrzaj\Sadrzaj;
use FireHub\Jezgra\Model\Model;
use FireHub\Aplikacija\NovaAvantura\Model\Rotator_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Artikli_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Favoriti_Model;
use FireHub\Aplikacija\NovaAvantura\Model\Blog_Model;

/**
 * ### Naslovna
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Kontroler
 */
final class Naslovna_Kontroler extends Master_Kontroler {

    protected Model $favoriti;

    /**
     * ## Konstruktor
     * @since 0.1.0.pre-alpha.M1
     *
     * @return void
     */
    public function __construct () {

        $this->favoriti = $this->model(Favoriti_Model::class);

        parent::__construct();

    }

    /**
     * ## index
     * @since 0.1.0.pre-alpha.M1
     *
     * @return Sadrzaj Sadržaj stranice.
     */
    public function index (BazaPodataka $bazaPodataka = null):Sadrzaj {

        $rotator = $this->model(Rotator_Model::class);
        $artikli_model = $this->model(Artikli_Model::class);
        $favoriti = $this->favoriti->artikli();
        $blog = $this->model(Blog_Model::class);

        // rotator
        $obavijest_html = '';
        foreach ($rotator->slike() as $obavijest) {

            $link = empty($obavijest['URL'])
                ? $obavijest['Link']
                    ? 'href="/artikl/'.$obavijest['Link'].'"'
                    : ''
                : 'href="'.$obavijest['URL'].'"';

            $obavijest_html .= "
            <a class='swiper-slide' $link>
                <picture>
                    <source srcset=\"/slika/baner/{$obavijest['Obavijest']}/700/1920\" media=\"(min-width: 768px)\" />
                    <source srcset=\"/slika/baner/{$obavijest['Obavijest2']}/768/768\" media=\"(max-width: 768px)\" />
                    <img src=\"/slika/baner/{$obavijest['Obavijest']}/700/1920\" alt=\"\" />
                </picture>
                <!--<img
                    srcset=\"
                        /slika/baner/{$obavijest['Obavijest']}/700/1920 1000w,
                        /slika/baner/{$obavijest['Obavijest2']}/768/768 768w,
                        /slika/baner/{$obavijest['Obavijest2']}/600/600 600w\"
                    sizes=\"(max-width: 600px) 600px, 1000px\"
                    src=\"/slika/baner/{$obavijest['Obavijest']}/700/1920 1000w\"
                    alt=\"\" loading=\"lazy\"
                />-->
            </a>
            ";

        }

        // izdvojeni artikli
        $artikli_izdvojeno_html = '';
        $artikli = $artikli_model->artikli(
            'izdvojeno', 0, 8, 'svi artikli', 0, PHP_INT_MAX, 'sve', 'sve', 'starost', 'desc'
        );
        foreach ($artikli as $artikal) {

            $fav = in_array($artikal['ID'], $favoriti)
                ? '<svg fill="red"><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti_puno"></use></svg>'
                : '<svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti"></use></svg>';

            $artikli_izdvojeno_html .= <<<Artikal
            
                <form class="artikal" method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="ID" value="{$artikal['ID']}" />
                    <a class="slika" href="/artikl/{$artikal['Link']}">
                        {$artikal['PopustHTML']}
                        {$artikal['NovoHTML']}
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    </a>
                    <span class="brand">
                        <button type="submit" class="gumb ikona" name="favorit_dodaj">
                            $fav
                        </button>
                        {$artikal['Brand']}
                     </span>
                    <a class="naziv" href="/artikl/{$artikal['Link']}">{$artikal['Naziv']}</a>
                    <span class="cijena">{$artikal['CijenaHTML']}</span>
                    <span class="cijena_30_dana">{$artikal['Cijena30DanaHTML']}</span>
                </form>

            Artikal;

        }

        // novi artikli
        $artikli_novo_html = '';
        $artikli = $artikli_model->artikli(
            'novo', 0, 8, 'svi artikli', 0, PHP_INT_MAX, 'sve', 'sve', 'starost', 'desc'
        );
        foreach ($artikli as $artikal) {

            $fav = in_array($artikal['ID'], $favoriti)
                ? '<svg fill="red"><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti_puno"></use></svg>'
                : '<svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti"></use></svg>';

            $artikli_novo_html .= <<<Artikal
            
                <form class="artikal" method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="ID" value="{$artikal['ID']}" />
                    <a class="slika" href="/artikl/{$artikal['Link']}">
                        {$artikal['PopustHTML']}
                        {$artikal['NovoHTML']}
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    </a>
                    <span class="brand">
                        <button type="submit" class="gumb ikona" name="favorit_dodaj">
                            $fav
                        </button>
                        {$artikal['Brand']}
                     </span>
                    <a class="naziv" href="/artikl/{$artikal['Link']}">{$artikal['Naziv']}</a>
                    <span class="cijena">{$artikal['CijenaHTML']}</span>
                    <span class="cijena_30_dana">{$artikal['Cijena30DanaHTML']}</span>
                </form>

            Artikal;

        }

        // outlet artikli
        $artikli_outlet_html = '';
        $artikli = $artikli_model->artikli(
            'outlet', 0, 8, 'svi artikli', 0, PHP_INT_MAX, 'sve', 'sve', 'starost', 'desc'
        );
        foreach ($artikli as $artikal) {

            $fav = in_array($artikal['ID'], $favoriti)
                ? '<svg fill="red"><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti_puno"></use></svg>'
                : '<svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti"></use></svg>';

            $artikli_outlet_html .= <<<Artikal
            
                <form class="artikal" method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="ID" value="{$artikal['ID']}" />
                    <a class="slika" href="/artikl/{$artikal['Link']}">
                        {$artikal['PopustHTML']}
                        {$artikal['NovoHTML']}
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    </a>
                    <span class="brand">
                        <button type="submit" class="gumb ikona" name="favorit_dodaj">
                            $fav
                        </button>
                        {$artikal['Brand']}
                     </span>
                    <a class="naziv" href="/artikl/{$artikal['Link']}">{$artikal['Naziv']}</a>
                    <span class="cijena">{$artikal['CijenaHTML']}</span>
                    <span class="cijena_30_dana">{$artikal['Cijena30DanaHTML']}</span>
                </form>

            Artikal;

        }

        // akcija artikli
        $artikli_akcija_html = '';
        $artikli = $artikli_model->artikli(
            'akcija', 0, 8, 'svi artikli', 0, PHP_INT_MAX, 'sve', 'sve', 'starost', 'desc'
        );
        foreach ($artikli as $artikal) {

            $fav = in_array($artikal['ID'], $favoriti)
                ? '<svg fill="red"><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti_puno"></use></svg>'
                : '<svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#favoriti"></use></svg>';

            $artikli_akcija_html .= <<<Artikal
            
                <form class="artikal" method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="ID" value="{$artikal['ID']}" />
                    <a class="slika" href="/artikl/{$artikal['Link']}">
                        {$artikal['PopustHTML']}
                        {$artikal['NovoHTML']}
                        <img src="/slika/malaslika/{$artikal['Slika']}" alt="" loading="lazy"/>
                    </a>
                    <span class="brand">
                        <button type="submit" class="gumb ikona" name="favorit_dodaj">
                            $fav
                        </button>
                        {$artikal['Brand']}
                     </span>
                    <a class="naziv" href="/artikl/{$artikal['Link']}">{$artikal['Naziv']}</a>
                    <span class="cijena">{$artikal['CijenaHTML']}</span>
                    <span class="cijena_30_dana">{$artikal['Cijena30DanaHTML']}</span>
                </form>

            Artikal;

        }

        // brandovi
        $brandovi = $artikli_model->brandovi(
            'sve kategorije', 'svi artikli', 0, PHP_INT_MAX, 'sve'
        );
        $brandovi_html = '';
        foreach ($brandovi as $brand) {

            $brand_link = mb_strtolower($brand['Brand']);

            $brandovi_html .= <<<Brand
            
                <div>
                    <a class="slika" href="/brand/{$brand_link}/" style="height: 100px; width: 200px;">
                        <img style="height: 80%; width: auto;" src="/slika/brand/{$brand['Slika']}" alt="" 
                        loading="lazy"/>
                    </a>
                </div>

            Brand;

        }

        // blogovi
        $blogovi = $blog->blogovi();
        $blogovi_html = '';

        foreach ($blogovi as $blog) {

            $blogovi_html .= <<<Blogovi
            
                <a class="a" href="/blog/{$blog['ID']}/">
                    <img src="/slika/blog/{$blog['Slika']}" alt="" loading="lazy"/>
                    <h4>{$blog['Naslov']}</h4>
                    <span>{$blog['Datum']}</span>
                </a>

            Blogovi;

        }

        return sadrzaj()->datoteka('naslovna.html')->podatci(array_merge($this->zadaniPodatci(), [
            'predlozak_naslov' => 'Naslovna',
            'obavijesti' => $obavijest_html,
            'artikli_izdvojeno' => $artikli_izdvojeno_html,
            'artikli_novo' => $artikli_novo_html,
            'artikli_outlet' => $artikli_outlet_html,
            'artikli_akcija' => $artikli_akcija_html,
            'brandovi' => $brandovi_html,
            'blogovi' => $blogovi_html
        ]));

    }

}