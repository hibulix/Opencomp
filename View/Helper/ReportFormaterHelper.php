<?php
App::uses('AppHelper', 'View/Helper');

/**
 * ReportFormaterHelper.php
 *
 * PHP version 5
 *
 * @category Helper
 * @package  Opencomp
 * @author   Jean Traullé <jean@opencomp.fr>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */

/**
 * Assistant de génération de bulletin en HTML
 *
 * @category Helper
 * @package  Opencomp
 * @author   Jean Traullé <jean@opencomp.fr>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */
class ReportFormaterHelper extends AppHelper
{

    /**
     * @var array Membre de classe permettant de stocker un record de type Report;
     */
    public $report;

    /**
     * @var array Membre de classe permettant de stocker un ensemble de record de type Competence
     * ainsi que les enregistrements associés.
     */
    public $competences;

    /**
     * @var array Membre de classe permettant de stocker un ensemble de record de type Item
     * ainsi que les enregistrements associés (résultats).
     */
    public $items;

    /**
     * Cette méthode permet de retourner un tableau contenant un item en ajoutant une couleur HTML
     * en fonction du résultat obtenu par l'élève.
     *
     * @param $item array Un tableau clé/valeur CakePHP contenant les propriétés d'un item et modèles associés
     * @return mixed array Identique à l'entrée mais avec une clé couleur en plus
     */
    public function itemWithResultColor($item){
        $item['Result']['color'] = "#FFFFFF";
        switch($item['Result']['result']){
            case 'A':
                $item['Result']['color'] = '#eeffcc';
                break;
            case 'B':
                $item['Result']['color'] = '#ffffbb';
                break;
            case 'C':
                $item['Result']['color'] = '#ffddaa';
                break;
            case 'D':
                $item['Result']['color'] = '#ffbbaa';
                break;
            case 'ABS':
                $item['Result']['color'] = '#eeeeee';
                break;
            case 'X':
                $item['Result']['result'] = '<img src="'.WWW_ROOT.'img/tick.png" alt="tick" />';
        }
        return $item;
    }

    /**
     * Cette méthode permet de remplacer les marqueurs définis par l'utilisateur dans l'en-tête d'un bulletin.
     *
     * @return string Titre du bulletin avec le nom et le prénom de l'élève remplacés.
     */
    public function formatHeader(){
        $header = $this->report['Report']['header'];
        $header = str_replace("#PRENOM#", $this->items[0]['Pupil']['first_name'], $header);
        $header = str_replace("#NOM#", $this->items[0]['Pupil']['name'], $header);
        return $header;
    }

    /**
     * Cette méthode permet de remplacer les marqueurs définis par l'utilisateur dans le pried de page d'un bulletin.
     *
     * @return string Pied de page du bulletin avec le nom et le prénom de l'élève remplacés.
     */
    public function formatFooter(){
        $footer = $this->report['Report']['footer'];
        $footer = str_replace("#PRENOM#", $this->items[0]['Pupil']['first_name'], $footer);
        $footer = str_replace("#NOM#", $this->items[0]['Pupil']['name'], $footer);
        return $footer;
    }

    /**
     * Cette méthode permet de retourner le corps principal du document (ensemble des compétences et items).
     *
     * @return string Chaîne HTML contenant le corps du bulletin.
     */
    public function getContent(){
        $html = "";
        foreach($this->competences as $competence){
            if(in_array($competence['id'], $this->report['Report']['page_break']))
                $html .= '<div style="page-break-after: always;"></div>';
            $html .= $this->returnHtmlFormattedCompetence($competence['id'], $competence['depth'], $competence['title'], $this->items);
        }
        return $html;
    }

    /**
     * Cette méthode permet de retourner une en-tête de compétence en fonction de la profondeur de celle-ci.
     *
     * @param $competence_id int L'identifiant de la compétence à traiter
     * @param $competence_depth int La profondeur dans l'arbre de la compétence à traiter
     * @param $competence_title string Le titre de la compétence à traiter
     * @param $items array Un tableau contenant l'ensemble des items pour le bulletin courant
     * @return string Une chaîne HTML contenant une en-tête de compétence (titre HTML ou en-tête de tableau)
     */
    public function returnHtmlFormattedCompetence($competence_id, $competence_depth, $competence_title, $items){
        if($competence_depth < 2){
            $html = sprintf('<h%d class="niveau%d">%s</h%d>',$competence_depth+1,$competence_depth+1,$competence_title,$competence_depth+1);
            $items = $this->returnHtmlItemsTableRows($competence_id, $items);
            if(!empty($items)) $html .= '<table><tbody>';
            $html .= $items;
            if(!empty($items)) $html .= '</tbody></table>';
        }else{
            $html = sprintf('<table class="tabniv%d">
                                <thead>
                                    <tr>
                                        <th colspan="2">%s</th>
                                    </tr>
                                </thead>
                                <tbody>',
                $competence_depth+1,
                $competence_title
            );
            $html .= $this->returnHtmlItemsTableRows($competence_id, $items);
            $html .= '</tbody></table>';
        }
        return $html;
    }

    /**
     * Cette méthode permet de retourner les items formatés en HTML liés à une compétence
     *
     * @param $competence_id int L'identifiant de la compétence à traiter
     * @param $items array Un tableau contenant l'ensemble des items pour le bulletin courant
     * @return string string Une chaîne HTML contenant des lignes de tableau HTML (<tr>)
     */
    public function returnHtmlItemsTableRows($competence_id, $items){
        $itemlist = null;
        foreach($items as $item){
            if($item['Item']['competence_id'] == $competence_id && $item['Result']['result'] != ""){
                $item = $this->itemWithResultColor($item);
                $itemlist[] = '<tr>
                                    <td>'.ucfirst($item['Item']['title']).'</td>
                                    <td style="text-align:center; background-color:'.$item['Result']['color'].';width:60px;">'.$item['Result']['result'].'</td>
                                </tr>';
            }
        }
        if(isset($itemlist))
            return implode("\n", $itemlist);
        else
            return "";
    }

    /**
     * Cette méthode permet de générer un fichier PDF basé sur du HTML
     *
     * @param $html string La chaîne HTML à transformer en fichier PDF
     * @param $classroom_id int L'identifiant de la classe de l'élève dont le bulletin est généré
     * @param $period_id int La période liée au bulletin généré
     * @param $pupil_id int L'identifiant de l'élève dont le bulletin est généré.
     */
    public function renderPdf($html, $pupil_id){
        if(!defined('DOMPDF_ENABLE_AUTOLOAD'))
            define('DOMPDF_ENABLE_AUTOLOAD', false);
        App::import('Vendor', 'Dompdf', array('file' => 'dompdf' . DS . 'dompdf' . DS . 'dompdf_config.inc.php'));

        $dompdf = new DOMPDF();
        $dompdf->set_paper("a4");
        $dompdf->load_html($html);
        $dompdf->render();

        //Si l'utilisateur a demandé l'impression recto/verso
        //on ajoute automatiquement des pages blanche si le bulletin
        //ne comporte pas un nombre pair de pages ;)
        if($this->report['Report']['duplex_printing'])
            if($dompdf->get_canvas()->get_page_count() % 2 == 1)
                $dompdf->get_canvas()->new_page();

        $pdfoutput = $dompdf->output();
        $filename = APP . "files/reports/".$this->report['Report']['id']."_".$pupil_id.".pdf";
        $fp = fopen($filename, "a");
        fwrite($fp, $pdfoutput);
        fclose($fp);
    }
}
