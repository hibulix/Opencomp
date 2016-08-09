<?php
namespace app\Controller;

use App\Controller\AppController;
use App\Controller\Component\LoadCSVComponent;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

/**
 * EstablishmentsController.php
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Opencomp
 * @author   Jean Traullé <jtraulle@gmail.com>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */

/**
 * Contrôleur de gestion des établissements scolaires
 *
 * @property LoadCSVComponent LoadCSV
 * @category Controller
 * @package  Opencomp
 * @author   Jean Traullé <jtraulle@gmail.com>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */
class EstablishmentsController extends AppController
{

    public $helpers = ['Time'];

    public $paginate = [
        'limit' => 15,
        'contain' => [
            'Towns.Academies'
        ],
        'sortWhitelist' => ['Towns.name', 'Academies.name', 'name', 'id', 'sector']
    ];

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('LoadCSV');
        $this->loadComponent('Paginator');

        $this->loadComponent('Search.Prg', [
            // This is default config. You can modify "actions" as needed to make
            // the PRG component work only for specified methods.
            'actions' => ['index', 'lookup']
        ]);
    }

    /**
     * @return void
     */
    public function index()
    {
        $query = $this->Establishments
            // Use the plugins 'search' custom finder and pass in the
            // processed query params
            ->find('search', ['search' => $this->request->query])
            // You can add extra things to the query if you need to
            ->contain(['Towns.Academies'])
            ->where(['Establishments.name IS NOT' => null]);

        $this->set('schools', $this->paginate($query));
        $this->set('_serialize', true);
    }

    /**
     * @return void
     */
    public function downloadWebEstablishments()
    {
        $src = 'https://www.data.gouv.fr/s/resources/adresse-et-geolocalisation-des-etablissements-denseignement-du-premier-et-second-degres/20160526-143453/DEPP-etab-1D2D.csv';
        $dest = TMP . 'mysql-files/DEPP-etab-1D2D.csv';
        $this->LoadCSV->downloadCSV($src, $dest);
    }

    /**
     * @return void
     */
    public function populateWebEstablishments()
    {
        $this->LoadCSV->populateCSV('/var/lib/mysql-files/DEPP-etab-1D2D.csv', 'web_establishments', 0, 'latin1', ';', 1);

        //On met à jour les établissements de la base de données depuis la table Web
        $conn = ConnectionManager::get('default');
        $conn->execute(
            "INSERT INTO establishments (`id`,`name`,`main_naming`,`uai_patronym`,`sector`, `address`, `locality`, `town_id`, `X`, `Y`) 
             SELECT `numero_uai`,`appellation_officielle`,`denomination_principale`,`patronyme_uai`,`secteur_public_prive_libe`,
                    `adresse_uai`, `lieu_dit_uai`, web_mapping_pc_insee.Code_commune_INSEE,
                    CONVERT(REPLACE(NULLIF(web_establishments.coordonnee_x,0),',','.'), DECIMAL(8,1)),
                    CONVERT(REPLACE(NULLIF(web_establishments.coordonnee_y,0),',','.'), DECIMAL(9,1))
             FROM `web_establishments` 
             LEFT JOIN web_mapping_pc_insee 
             ON web_mapping_pc_insee.Code_postal = web_establishments.code_postal_uai 
             AND web_mapping_pc_insee.Libelle_acheminement = web_establishments.localite_acheminement_uai 
             WHERE `nature_uai` IN(151,152,153) 
             AND web_mapping_pc_insee.Code_commune_INSEE IS NOT NULL 
             ON DUPLICATE KEY UPDATE
             `main_naming`=`denomination_principale`,
             `uai_patronym`=`patronyme_uai`,
             `locality`=`lieu_dit_uai`,
             X=CONVERT(REPLACE(NULLIF(web_establishments.coordonnee_x,0),',','.'), DECIMAL(8,1)), 
             Y=CONVERT(REPLACE(NULLIF(web_establishments.coordonnee_y,0),',','.'), DECIMAL(9,1))"
        );
    }

    /**
     * @return void
     */
    public function downloadGeoRef()
    {
        $src = 'http://data.enseignementsup-recherche.gouv.fr/explore/dataset/fr-esr-referentiel-geographique/download/?format=csv&timezone=Europe/Berlin&use_labels_for_header=true';
        $dest = TMP . 'mysql-files/fr-esr-referentiel-geographique.csv';
        $this->LoadCSV->downloadCSV($src, $dest);
    }

    /**
     * @return void
     */
    public function populateWebGeoRef()
    {
        $this->LoadCSV->populateCSV('/var/lib/mysql-files/fr-esr-referentiel-geographique.csv', 'web_geo_ref', 0, 'utf8', ';', 1);

        /*** @var Connection $conn */
        $conn = ConnectionManager::get('default');
        $conn->execute(
            'INSERT INTO towns (`id`,`name`,`academy_id`) 
             SELECT DISTINCT `COM_CODE`, `COM_NOM`, `ACA_CODE` 
             FROM web_geo_ref 
             ON DUPLICATE KEY UPDATE `name`=`COM_NOM`,`academy_id`=`ACA_CODE`'
        );
        $conn->execute(
            'INSERT INTO academies (`id`, `name`) 
             SELECT DISTINCT `ACA_CODE`, `ACA_NOM` 
             FROM web_geo_ref 
             ON DUPLICATE KEY UPDATE `name`=`ACA_NOM`'
        );
    }

    /**
     * @return void
     */
    public function downloadWebMappingCPINSEE()
    {
        $src = 'http://datanova.legroupe.laposte.fr/explore/dataset/laposte_hexasmal/download/?format=csv&timezone=Europe/Berlin&use_labels_for_header=true';
        $dest = TMP . 'mysql-files/laposte_hexasmal.csv';
        $this->LoadCSV->downloadCSV($src, $dest);
    }

    /**
     * @return void
     */
    public function populateWebMappingCPINSEE()
    {
        $this->LoadCSV->populateCSV('/var/lib/mysql-files/laposte_hexasmal.csv', 'web_mapping_pc_insee', 1, 'utf8', ';', 1);
    }

    /**
     * @return void
     */
    public function sync()
    {
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param int $id establishment id
     * @return void
     */
    public function view($id = null)
    {
        $this->set('title_for_layout', __('Visualiser un établissement scolaire'));

        $this->Settings = TableRegistry::get('Settings');
        $currentYear = $this->Settings->find('all', ['conditions' => ['Settings.key' => 'currentYear']])->first();

        //On récupère l'établissement, les classes et les périodes correspondant à l'année courante.
        $establishment = $this->Establishments->get(
            $id,
            [
                'contain' => [
                    'Users',
                    'Towns.Academies',
                    'Periods' => [
                        'conditions' => ['Periods.year_id =' => $currentYear->value]],
                    'Periods.Years',
                    'Classrooms' => [
                        'conditions' => ['Classrooms.year_id =' => $currentYear->value]],
                    'Classrooms.Users' => [
                        'conditions' => ['ClassroomsUsers.ownership =' => 'OWNER']],
                    'Classrooms.Years'
                ]
            ]
        );

        $this->set('stats', $this->Establishments->getStats($id, $currentYear));
        $this->set('blank_period', $this->Establishments->Periods->newEntity());
        $this->set('establishment', $establishment);
        $this->set('lat', $establishment->lat);
        $this->set('lgt', $establishment->lgt);
        $this->set('current_year', $currentYear->value);
    }

    /**
     * add method
     *
     * @return \Cake\Network\Response|null
     */
    public function add()
    {
        $this->set('title_for_layout', __('Ajouter un établissement scolaire'));

        $establishment = $this->Establishments->newEntity();
        if ($this->request->is('post')) {
            $establishment = $this->Establishments->newEntity($this->request->data);
            if ($this->Establishments->save($establishment)) {
                $this->Flash->success('Le nouvel établissement scolaire a été correctement ajouté.');
                

                return $this->redirect(['controller' => 'Academies', 'action' => 'view', $establishment->academy_id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        //Si on a passé un academy_id en paramètre, on présélectionne la liste déroulante avec la valeur passée.
        if (isset($this->request->query['academy_id'])) {
            $this->set('academy_id', $this->request->query['academy_id']);
        } else {
            $this->set('academy_id', null);
        }

        $users = $this->Establishments->Users->find('list');
        $academies = $this->Establishments->Academies->find('list');
        $this->set(compact('users', 'academies', 'establishment'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param int $id establishment id
     * @return \Cake\Network\Response|null
     */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Modifier un établissement scolaire'));

        $establishment = $this->Establishments->get($id, ['contain' => ['Towns.Academies']]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $establishment = $this->Establishments->patchEntity($establishment, $this->request->data);
            if ($this->Establishments->save($establishment)) {
                $this->Flash->success('L\'établissement a été correctement mis à jour');
                

                return $this->redirect(['controller' => 'Establishments', 'action' => 'view', $establishment->id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }
        $users = $this->Establishments->Users->find('list')->toArray();
        //$towns = $this->Establishments->Towns->find('list');
        $this->set(compact('establishment', 'towns', 'users'));
    }
}
