<?php declare(strict_types = 1);

/**
 * Rute za HTTP pozive aplikacije
 * @since 0.4.1.pre-alpha.M4
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Sustav\Jezgra
 */

use FireHub\Jezgra\Komponente\Rute\Rute;

Rute::sve('prijava/index', [\FireHub\Aplikacija\Administrator\Kontroler\Prijava_Kontroler::class, 'index']);
Rute::sve('prijava/autorizacija', [\FireHub\Aplikacija\Administrator\Kontroler\Prijava_Kontroler::class, 'autorizacija']);
Rute::sve('odjava/index', [\FireHub\Aplikacija\Administrator\Kontroler\Odjava_Kontroler::class, 'index']);
Rute::sve('naslovna/index', [\FireHub\Aplikacija\Administrator\Kontroler\Naslovna_Kontroler::class, 'index']);
Rute::sve('artikli/index', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'index']);
Rute::sve('artikli/lista', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'lista']);
Rute::sve('artikli/novi', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'novi']);
Rute::sve('artikli/uredi', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'uredi']);
Rute::sve('artikli/uredizalihu', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'uredizalihu']);
Rute::sve('artikli/zalihaspremi', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'zalihaspremi']);
Rute::sve('artikli/artiklsifre', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'artiklsifre']);
Rute::sve('artikli/artiklsifrespremi', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'artiklsifrespremi']);
Rute::sve('artikli/artiklsifreizbrisi', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'artiklsifreizbrisi']);
Rute::sve('artikli/spremi', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'spremi']);
Rute::sve('artikli/dodajsliku', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'dodajsliku']);
Rute::sve('artikli/izbrisisliku', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'izbrisisliku']);
Rute::sve('artikli/izbrisicijenu', [\FireHub\Aplikacija\Administrator\Kontroler\Artikli_Kontroler::class, 'izbrisicijenu']);
Rute::sve('kategorije/index', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'index']);
Rute::sve('kategorije/lista', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'lista']);
Rute::sve('kategorije/uredi', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'uredi']);
Rute::sve('kategorije/spremi', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'spremi']);
Rute::sve('kategorije/nova', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'nova']);
Rute::sve('kategorije/izbrisi', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'izbrisi']);
Rute::sve('kategorije/dodajsliku', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'dodajsliku']);
Rute::sve('brandovi/index', [\FireHub\Aplikacija\Administrator\Kontroler\Brandovi_Kontroler::class, 'index']);
Rute::sve('brandovi/lista', [\FireHub\Aplikacija\Administrator\Kontroler\Brandovi_Kontroler::class, 'lista']);
Rute::sve('brandovi/uredi', [\FireHub\Aplikacija\Administrator\Kontroler\Brandovi_Kontroler::class, 'uredi']);
Rute::sve('brandovi/spremi', [\FireHub\Aplikacija\Administrator\Kontroler\Brandovi_Kontroler::class, 'spremi']);
Rute::sve('brandovi/nova', [\FireHub\Aplikacija\Administrator\Kontroler\Brandovi_Kontroler::class, 'nova']);
Rute::sve('brandovi/izbrisi', [\FireHub\Aplikacija\Administrator\Kontroler\Brandovi_Kontroler::class, 'izbrisi']);
Rute::sve('brandovi/dodajsliku', [\FireHub\Aplikacija\Administrator\Kontroler\Brandovi_Kontroler::class, 'dodajsliku']);
Rute::sve('obavijesti/index', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'index']);
Rute::sve('obavijesti/lista', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'lista']);
Rute::sve('obavijesti/uredi', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'uredi']);
Rute::sve('obavijesti/spremi', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'spremi']);
Rute::sve('obavijesti/izbrisi', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'izbrisi']);
Rute::sve('obavijesti/dodaj', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'dodaj']);
Rute::sve('obavijesti/dodajsliku', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'dodajSliku']);