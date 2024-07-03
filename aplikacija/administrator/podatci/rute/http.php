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
Rute::sve('kategorije/index', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'index']);
Rute::sve('kategorije/lista', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'lista']);
Rute::sve('kategorije/uredi', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'uredi']);
Rute::sve('kategorije/spremi', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'spremi']);
Rute::sve('kategorije/nova', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'nova']);
Rute::sve('kategorije/izbrisi', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'izbrisi']);
Rute::sve('kategorije/dodajsliku', [\FireHub\Aplikacija\Administrator\Kontroler\Kategorije_Kontroler::class, 'dodajsliku']);
Rute::sve('obavijesti/index', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'index']);
Rute::sve('obavijesti/lista', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'lista']);
Rute::sve('obavijesti/uredi', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'uredi']);
Rute::sve('obavijesti/spremi', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'spremi']);
Rute::sve('obavijesti/izbrisi', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'izbrisi']);
Rute::sve('obavijesti/dodaj', [\FireHub\Aplikacija\Administrator\Kontroler\Obavijesti_Kontroler::class, 'dodaj']);