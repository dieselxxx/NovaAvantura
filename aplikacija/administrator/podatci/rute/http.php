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

Rute::sve('naslovna/index', [\FireHub\Aplikacija\Administrator\Kontroler\Naslovna_Kontroler::class, 'index']);