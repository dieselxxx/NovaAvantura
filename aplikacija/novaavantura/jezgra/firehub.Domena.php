<?php declare(strict_types = 1);

/**
 * Domena
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2024 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Kontroler
 */

namespace FireHub\Aplikacija\NovaAvantura\Jezgra;

final class Domena {

    /**
     * ## Da li je BA stranica
     * @since 0.1.0.pre-alpha.M1
     *
     * @return bool
     */
    public static function Ba ():bool {

        if (Server::Domena() === 'www.nova-avantura.ba' || Server::Domena() === 'test.nova-avantura.ba') {

            return true;

        }

        return false;

    }

    /**
     * ## Da li je HR stranica
     * @since 0.1.0.pre-alpha.M1
     *
     * @return bool
     */
    public static function Hr ():bool {

        if (Server::Domena() === 'nova-avantura.hr' || Server::Domena() === 'test.nova-avantura.hr' || Server::Domena() === 'localhost:210') {


            return true;

        }

        return false;

    }

    /**
     * ## Opis head
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function opis ():string {

        if (self::Hr()) {

            return 'Nova Avantura d.o.o.';

        }

        return 'Nova Avantura d.o.o.';

    }

    /**
     * ## SQL prefix tablica
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function sqlTablica ():string {

        if (self::Hr()) {

            return 'Hr';

        }

        return 'Ba';

    }

    /**
     * ## SQL prefix cijena
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function sqlCijena ():string {

        if (self::Hr()) {

            return 'CijenaKn';

        }

        return 'Cijena';

    }


    /**
     * ## SQL prefix cijena akcija
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function sqlCijenaAkcija ():string {

        if (self::Hr()) {

            return 'CijenaAkcijaKn';

        }

        return 'CijenaAkcija';

    }

    /**
     * ## SQL prefix outlet
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function sqlOutlet ():string {

        if (self::Hr()) {

            return 'OutletHr';

        }

        return 'Outlet';

    }

    /**
     * ## Valuta
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function valuta ():string {

        if (self::Hr()) {

            return '€';

        }

        return 'KM';

    }

    /**
     * ## Valuta ISO
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function valutaISO ():string {

        if (self::Hr()) {

            return 'EUR';

        }

        return 'BAM';

    }

    /**
     * ## Broj telefona
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function telefon ():string {

        if (self::Hr()) {

            return '+385 12 3456 789';

        }

        return '+387 12 345 678';

    }

    /**
     * ## Email
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function email ():string {

        if (self::Hr()) {

            return 'info@nova-avanutura.hr';

        }

        return 'info@nova-avanutura.ba';

    }

    /**
     * ## Adresa
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function adresa ():string {

        if (self::Hr()) {

            return 'Adresa bb, 112233 Grad';

        }

        return 'Adresa bb, 112233 Grad';

    }

    /**
     * ## Stranica poslovnice
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function poslovnice ():string {

        if (self::Hr()) {

            return 'poslovnice_hr.html';

        }

        return 'poslovnice.html';

    }

    /**
     * ## Dostava
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function podnozjeDostava ():string {

        if (self::Hr()) {

            return '
                <ul>
                    <li>
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Besplatna dostava za narudzbe preko 149 €.</span>
                    </li>
                    <li>
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Dostava za narudzbe manje od 149 € iznosi 6 €.</span>
                    </li>
                    <li>
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Dostava brzom poštom u roku 24-48 h.</span>
                    </li>
                    <li>
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Mogućnost plaćanja pouzećem, općom uplatnicom, internet bankarstvom.</span>
                    </li>
                </ul>
            ';

        }

        return '
                <ul>
                    <li>
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Besplatna dostava za narudzbe preko 149 KM.</span>
                    </li>
                    <li>
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Dostava za narudzbe manje od 149 KM iznosi 9 KM.</span>
                    </li>
                    <li>
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Dostava brzom poštom u roku 24-48 h.</span>
                    </li>
                    <li>
                        <svg><use xlink:href="/novaavantura/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo2"></use></svg>
                        <span>Mogućnost plaćanja pouzećem, općom uplatnicom, internet bankarstvom.</span>
                    </li>
                </ul>
            ';

    }

    /**
     * ## OIB ili PDV
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function OIBPDV ():string {

        if (self::Hr()) {

            return 'OIB';

        }

        return 'PDV';

    }

    /**
     * ## Iznos limita dostave
     * @since 0.1.0.pre-alpha.M1
     *
     * @return int|float
     */
    public static function dostavaLimit ():int|float {

        if (self::Hr()) {

            return 149;

        }

        return 149;

    }

    /**
     * ## Iznos dostave
     * @since 0.1.0.pre-alpha.M1
     *
     * @return int|float
     */
    public static function dostavaIznos ():int|float {

        if (self::Hr()) {

            return 6;

        }

        return 9;

    }

    /**
     * ## Država
     * @since 0.1.0.pre-alpha.M1
     *
     * @return int|float
     */
    public static function drzavaID ():int|float {

        if (self::Hr()) {

            return 2;

        }

        return 1;

    }

    /**
     * ## Facebook link
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function facebook ():string {

        if (self::Hr()) {

            return 'https://www.facebook.com/novaavantura.hr';

        }

        return 'https://www.facebook.com/novaavantura.ba';

    }

    /**
     * ## Instagram link
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public static function instagram ():string {

        if (self::Hr()) {

            return 'https://www.instagram.com/novaavantura.hr/';

        }

        return 'https://www.instagram.com/novaavantura.sa/';

    }

    /**
     * ## Black Friday
     * @since 0.1.0.pre-alpha.M1
     *
     * @return bool
     */
    public static function blackFriday ():bool {

        if (date("m-d") >= '11-25' && date("m-d") <= '11-25') {

            return true;

        }

        return false;

    }

    /**
     * ## Black Friday popust
     * @since 0.1.0.pre-alpha.M1
     *
     * @return float
     */
    public static function blackFridayPopust ():float {

        return 0.15;

    }

}