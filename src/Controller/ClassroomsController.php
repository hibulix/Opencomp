<?php
namespace app\Controller;

//noinspection PhpUnusedClassInspection
use /** @noinspection PhpUnusedAliasInspection */
    App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Database\Query;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;

use Cake\Utility\Text;

/**
 * Classrooms Controller
 *
 */
class ClassroomsController extends AppController
{

    public $paginate = [
        'Evaluations' => [
            'limit' => 15
        ]
    ];

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Search.Prg', [
            'actions' => ['viewtests']
        ]);
    }

    /**
     *
     * @param Event $event Cake event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow('getJson');
    }

    /**
     *
     * @param int $id Classroom id
     * @return Response
     */
    public function opendocumentdatabase($id)
    {
        $classroom = $this->Classrooms->get($id, ['contain' => ['Establishments.Towns.Academies']]);
        $user = $this->Classrooms->Users->get($this->Auth->user('id'));
        $url = 'http://java_servlets:8080/ODBGenerator/generateODB?apikey=' . $user->api_token . '&classroom_id=' . $id;

        // Création d'un gestionnaire curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        // Exécution
        $res = curl_exec($ch);

        // Vérification si une erreur est survenue
        if (curl_errno($ch)) {
            $this->Flash->error('Le serveur passerelle de génération de base de données OpenDocument Database a retourné le code d\'erreur HTTP ' . curl_getinfo($ch, CURLINFO_HTTP_CODE));
            curl_close($ch);


            return $this->redirect([
                'controller' => 'classrooms',
                'action' => 'view', $id
            ]);
        } else {
            // Fermeture du gestionnaire
            curl_close($ch);

            $filename = Text::slug($classroom->establishment->town->academy->name . " " . $classroom->establishment->town->name . " " . $classroom->establishment->id . " " . $classroom->title);
            $this->response->body($res);
            $this->response->download(strtolower($filename) . '.odb');
            //Retourne un objet réponse pour éviter que le controller n'essaie de
            // rendre la vue
            return $this->response;
        }
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id Classroom_id
     * @return void
     */
    public function view($id = null)
    {
        $this->set('title_for_layout', __('Visualiser une classe'));

        $classroom = $this->Classrooms->get($id, [
            'contain' => ['Users', 'Establishments', 'Years', 'Pupils.Levels']
        ]);

        $classroomsPupils = $this->Classrooms->findPupilsByLevelsInClassroom($id);

        if ($this->request->query('format') === 'select2') {
            $classroom = $this->Classrooms->getPupilsSelect2($id);
        }

        $this->set(compact('classroom', 'classroomsPupils'));

        $this->set('_serialize', 'classroom');
    }

    /**
     *
     * @param int $id Classroom id
     * @return void
     */
    public function viewtests($id = null)
    {
        $this->set('title_for_layout', __('Visualiser une classe'));

        $classroom = $this->Classrooms->get($id, ['contain' => ['Establishments', 'Years']]);

        $evaluationsQuery = $this->Classrooms->Evaluations->find('search', ['search' => $this->request->query])
            ->select(['id', 'title'])
            ->where(['classroom_id' => $id, 'unrated' => 0])
            ->contain(['Users' =>
                function (Query $q) {
                    return $q
                        ->select(['id', 'first_name', 'last_name']);
                }, 'Results' => function (Query $q) {
                    return $q
                        ->select(['id', 'evaluation_id']);
                }, 'Pupils' => function (Query $q) {
                    return $q
                        ->select(['id']);
                }, 'Competences' => function (Query $q) {
                    return $q
                        ->select(['id']);
                }])
            ->orderDesc('id');

        if ($this->request->is('json')) {
            $evaluationsQuery = $this->Classrooms->Evaluations->find('search', ['search' => $this->request->query])
                ->select(['Evaluations.id', 'Evaluations.title', 'created', 'Periods.id', 'Periods.begin', 'Periods.end'])
                ->contain(['Users' => function (Query $q) {
                    return $q
                        ->select(['id', 'first_name', 'last_name']);
                }, 'Periods'])
                ->where(['classroom_id' => $id, 'unrated' => 0])
                ->orderDesc('Evaluations.id');
        }

        $evaluations = $this->paginate($evaluationsQuery);

        $periods = $this->Classrooms->Evaluations->find()
            ->select(['period_id', 'Periods.begin', 'Periods.end'])->distinct()
            ->contain(['Periods'])
            ->where([
                'Periods.year_id' => $classroom->year->id,
                'Periods.establishment_id' => $classroom->establishment->id
            ])
            ->all();

        $this->set(compact('classroom', 'evaluations', 'periods'));
        $this->set('_serialize', 'evaluations');
    }

    /**
     *
     * @param int $id Classroom id
     * @return void
     */
    public function viewunrateditems($id = null)
    {
        $this->set('title_for_layout', __('Visualiser une classe'));

        $classroom = $this->Classrooms->get($id, ['contain' => ['Establishments']]);

        $evaluations = $this->Classrooms->Evaluations->find()
            ->select(['Evaluations.id', 'Periods.begin', 'Periods.end'])
            ->contain(['Competences', 'Periods'])
            ->where(['classroom_id' => $id, 'unrated' => 1])
            ->orderDesc('Evaluations.id')->all();

        $this->set(compact('classroom', 'evaluations'));
    }

    /**
     *
     * @param int $id Classroom id
     * @return void
     */
    public function viewreports($id = null)
    {
        $this->set('title_for_layout', __('Bulletins d\'une classe'));

        $classroom = $this->Classrooms->get($id, ['contain' => ['Users', 'Establishments', 'Years', 'Reports']]);
        $this->set('classroom', $classroom);

        $periods = $this->Classrooms->Evaluations->Periods->find('list', [
            'conditions' => ['establishment_id' => $classroom->establishment_id]])->toArray();
        $this->set('periods', $periods);
    }

    /**
     * add method
     *
     * @param int $id Classroom id
     * @return void
     */
    public function add($id)
    {
        $this->set('title_for_layout', __('Ajouter une classe'));

        $establishment = $this->Classrooms->Establishments->get($id);

        $classroom = $this->Classrooms->newEntity();
        if ($this->request->is('post')) {
            $classroom = $this->Classrooms->newEntity($this->request->data);

            $this->Settings = TableRegistry::get('Settings');
            $currentYear = $this->Settings->find('all', ['conditions' => ['Settings.key' => 'currentYear']])->first();
            $currentYear = $currentYear->value;

            $classroom->year_id = $currentYear;
            $classroom->establishment_id = $establishment->id;

            if ($this->Classrooms->save($classroom)) {
                $this->Flash->success('La nouvelle classe a été correctement ajoutée.');
                $this->redirect([
                    'controller' => 'establishments',
                    'action' => 'view', $classroom->establishment_id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        $users = $this->Classrooms->Users->find('list');
        $this->set('establishment_id', $establishment->id);
        $this->set(compact('classroom', 'users', 'establishments'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param int $id classroom id
     * @return void
     */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Modifier une classe'));
        $classroom = $this->Classrooms->get($id, [
            'contain' => 'Users'
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $classroom = $this->Classrooms->patchEntity($classroom, $this->request->data);
            if ($this->Classrooms->save($classroom)) {
                $this->Flash->success('La classe a été correctement modifiée.');
                $this->redirect([
                    'controller' => 'classrooms',
                    'action' => 'view', $classroom->establishment_id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        $users = $this->Classrooms->Users->find('list');
        $this->set(compact('classroom', 'users'));
    }
}
