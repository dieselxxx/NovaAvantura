<?php declare(strict_types = 1);

/**
 * Blogovi model
 * @since 0.1.0.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2024 Nova Avantura Web Trgovina
 * @license GNU General Public License version 3 - [https://opensource.org/licenses/GPL-3.0](https://opensource.org/licenses/GPL-3.0)
 *
 * @version 1.0
 * @package Aplikacija\Model
 */

namespace FireHub\Aplikacija\Administrator\Model;

use FireHub\Jezgra\Komponente\BazaPodataka\BazaPodataka;
use FireHub\Aplikacija\NovaAvantura\Jezgra\Validacija;
use FireHub\Aplikacija\Administrator\Jezgra\tTablicaEfekti;
use FireHub\Jezgra\Greske\Greska;

/**
 * ### Blogovi
 * @since 0.1.0.pre-alpha.M1
 *
 * @package Aplikacija\Model
 */
final class Blogovi_Model extends Master_Model {

    use tTablicaEfekti;

    private int $broj_stranice;
    protected string $poredaj;
    protected string $redoslijed;
    private string $pretraga;
    public int $limit_zapisa_po_stranici;

    /**
     * ### Konstruktor
     * @since 0.1.0.pre-alpha.M1
     */
    public function __construct (
        private BazaPodataka $bazaPodataka
    ){

        parent::__construct();

    }

    /**
     * Lista blogova
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $broj_stranice
     * @param string $poredaj
     * @param string $redoslijed
     *
     * @throws Greska
     *
     * @return array
     */
    public function lista (int $broj_stranice = 1, string $poredaj = 'Datum', string $redoslijed = 'desc', int $limit_zapisa_po_stranici = 10):array {

        if (isset($_REQUEST['pretraga']) && $_REQUEST['pretraga'] <> '') {

            $pretraga = $_REQUEST['pretraga'];

        } else {

            $pretraga = '';

        }

        $pretraga = Validacija::String(_('Pretraga'), $pretraga, 0, 50);
        $broj_stranice = Validacija::Broj(_('Broj stranice'), $broj_stranice, 1, 10);

        $this->broj_stranice = $broj_stranice;

        $this->pretraga = $pretraga;

        $this->poredaj = $poredaj;
        $this->poredaj = Validacija::Slova(_('Poredaj rezutate'), $this->poredaj, 1);

        $this->redoslijed = $redoslijed;
        $this->redoslijed = Validacija::Slova(_('Redoslijed rezultata'), $this->redoslijed, 3, 4);
        if ($this->redoslijed <> 'asc' && $this->redoslijed <> 'desc') {throw new Greska(_('Redoslijed rezultata ima pogrešnu vrijednost'));}

        $this->limit_zapisa_po_stranici = $limit_zapisa_po_stranici;

        // potraži blogove
        $pomak = ($broj_stranice - 1) * $this->limit_zapisa_po_stranici;

        $rezultat = $this->bazaPodataka
            ->sirovi("
                SELECT
                    blog.ID, blog.Naslov, blog.Opis, blog.Datum
                FROM blog
                WHERE blog.ID <> 0
                {$this->trazi()}
                ORDER BY ".ucwords($poredaj)." $redoslijed
                LIMIT $pomak, $this->limit_zapisa_po_stranici
            ")
            ->napravi();

        return $rezultat->niz() ?: [];

    }

    /**
     * ### Traži blogove
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string Upit za traženje.
     */
    private function trazi ():string {

        if (isset($_POST['pretraga'])) {

            $trazi = explode(' ', (string)$_POST['pretraga']);

            $trazi_array = '';
            foreach ($trazi as $stavka) {

                $trazi_array .= "
                    AND (
                        blog.Naslov LIKE '%{$stavka}%'
                    )
                ";

            }

            return $trazi_array;

        }

        return '';

    }

    /**
     * Zaglavlje blogova
     * @since 0.1.0.pre-alpha.M1
     *
     * @return string
     */
    public function IspisiZaglavlje () {

        return '
            <tr>
                <th width="15%" onclick="$_Blogovi(this,1,\'ID\',\''.$this->RedoslijedObrnuto().'\')">
                    <div>
                        <span>'._('ID').'</span>
                        <div class="poredaj">
                            <svg class="gore '.$this->RedoslijedIkona('ID', 'desc').'"><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#strelica_gore"></use></svg>
                            <svg class="dole '.$this->RedoslijedIkona('ID', 'asc').'"><use xlink:href="/administrator/resursi/grafika/simboli/simbol.ikone.svg#strelica_dole"></use></svg>
                        </div>
                    </div>
                </th>
                <th width="25%" onclick="$_Blogovi(this,1,\'Datum\',\''.$this->RedoslijedObrnuto().'\')">
                    <div>
                        <span>'._('Datum').'</span>
                        <div class="poredaj">
                            <svg class="gore '.$this->RedoslijedIkona('Datum', 'desc').'"><use xlink:href="/imovina/grafika/simboli/simbol.ikone.svg#strelica_gore"></use></svg>
                            <svg class="dole '.$this->RedoslijedIkona('Datum', 'asc').'"><use xlink:href="/imovina/grafika/simboli/simbol.ikone.svg#strelica_dole"></use></svg>
                        </div>
                    </div>
                </th>
                <th width="60%" onclick="$_Blogovi(this,1,\'Naslov\',\''.$this->RedoslijedObrnuto().'\')">
                    <div>
                        <span>'._('Naslov').'</span>
                        <div class="poredaj">
                            <svg class="gore '.$this->RedoslijedIkona('Naslov', 'desc').'"><use xlink:href="/imovina/grafika/simboli/simbol.ikone.svg#strelica_gore"></use></svg>
                            <svg class="dole '.$this->RedoslijedIkona('Naslov', 'asc').'"><use xlink:href="/imovina/grafika/simboli/simbol.ikone.svg#strelica_dole"></use></svg>
                        </div>
                    </div>
                </th>
            </tr>
        ';

    }

    /**
     * Navigacija
     * @since 0.1.0.pre-alpha.M1
     *
     * @param int $broj_stranice
     *
     * @return array
     */
    public function IspisiNavigaciju (int $broj_stranice = 1):array {

        $pocetak_link_stranice = "";
        $link_stranice = "";
        $kraj_link_stranice = "";

        $ukupno_stranica = ceil($this->brojZapisa() / $this->limit_zapisa_po_stranici);
        if (($this->broj_stranice - 2) < 1) {$x = 1;} else {$x = ($this->broj_stranice - 2);}
        if (($this->broj_stranice + 2) >= $ukupno_stranica) {$y = $ukupno_stranica;} else {$y = ($this->broj_stranice + 2);}

        $obavijesti = '$_Blogovi';

        if ($this->broj_stranice >= 2) {

            $prosla_stranica = $this->broj_stranice - 1;

            $pocetak_link_stranice .= "<li><a class='gumb mali ikona' onclick='{$obavijesti}(this,1,\"{$this->poredaj}\",\"{$this->redoslijed}\")'><svg data-boja='boja'><use xlink:href=\"/administrator/resursi/grafika/simboli/simbol.ikone.svg#strelica_lijevo_duplo\" /></svg></a></li>";
            $pocetak_link_stranice .= "<li><a class='gumb mali ikona' onclick='{$obavijesti}(this,$prosla_stranica,\"{$this->poredaj}\",\"{$this->redoslijed}\")'><svg data-boja='boja'><use xlink:href=\"/administrator/resursi/grafika/simboli/simbol.ikone.svg#strelica_lijevo\" /></svg></a></li>";

        }

        for ($i = $x; $i <= $y; $i++) {

            if ($i == $this->broj_stranice) {

                $link_stranice .= "<li><a class='gumb mali' data-boja='boja' onclick='{$obavijesti}(this,$i,\"{$this->poredaj}\",\"{$this->redoslijed}\")'>{$i}</a></li>";

            }  else {

                $link_stranice .= "<li><a class='gumb mali' onclick='{$obavijesti}(this,$i,\"{$this->poredaj}\",\"{$this->redoslijed}\")'>{$i}</a></li>";

            }

        }

        if ($this->broj_stranice < $ukupno_stranica) {

            $sljedeca_stranica = $this->broj_stranice + 1;

            $kraj_link_stranice .= "<li><a class='gumb mali ikona' onclick='{$obavijesti}(this,$sljedeca_stranica,\"{$this->poredaj}\",\"{$this->redoslijed}\")'><svg data-boja='boja'><use xlink:href=\"/administrator/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno\" /></svg></a></li>";
            $kraj_link_stranice .= "<li><a class='gumb mali ikona' onclick='{$obavijesti}(this,$ukupno_stranica,\"{$this->poredaj}\",\"{$this->redoslijed}\")'><svg data-boja='boja'><use xlink:href=\"/administrator/resursi/grafika/simboli/simbol.ikone.svg#strelica_desno_duplo\" /></svg></a></li>";

        }

        return array('pocetak' => $pocetak_link_stranice, 'stranice' => $link_stranice, 'kraj' => $kraj_link_stranice);

    }

    /**
     * Broj zapisa
     * @since 0.1.0.pre-alpha.M1
     *
     * @return int
     */
    private function brojZapisa():int {

        $rezultat = $this->bazaPodataka
            ->sirovi("
                SELECT
                    ID
                FROM blog
                WHERE ID <> 0
                {$this->trazi()}
            ")
            ->napravi();

        return $rezultat->broj_zapisa();

    }

}