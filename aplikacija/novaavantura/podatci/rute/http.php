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

Rute::sve('naslovna/index', [\FireHub\Aplikacija\NovaAvantura\Kontroler\Naslovna_Kontroler::class, 'index']);
Rute::sve('slika/baner', [\FireHub\Aplikacija\NovaAvantura\Kontroler\Slika_Kontroler::class, 'baner']);
Rute::sve('slika/malaslika', [\FireHub\Aplikacija\NovaAvantura\Kontroler\Slika_Kontroler::class, 'malaslika']);
Rute::sve('slika/velikaslika', [\FireHub\Aplikacija\NovaAvantura\Kontroler\Slika_Kontroler::class, 'velikaslika']);
Rute::sve('slika/kategorija', [\FireHub\Aplikacija\NovaAvantura\Kontroler\Slika_Kontroler::class, 'kategorija']);
Rute::sve('kolacic/index', [\FireHub\Aplikacija\NovaAvantura\Kontroler\Kolacic_Kontroler::class, 'index']);
Rute::sve('kolacic/osobnipodatci', [\FireHub\Aplikacija\NovaAvantura\Kontroler\Kolacic_Kontroler::class, 'osobnipodatci']);
Rute::sve('kosarica/narudzba', [\FireHub\Aplikacija\NovaAvantura\Kontroler\Kosarica_Kontroler::class, 'narudzba']);