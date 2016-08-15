<?php
namespace app\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;

/**
 * Lpcnodes Controller
 *
 * @property Lpcnode $Lpcnode
 * @property PaginatorComponent $Paginator
 */
class LpcnodesController extends AppController
{

    /**
     * Helpers
     *
     * @var array
     */
    public $components = ['JsonTree'];

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('title_for_layout', __('Livret Personnel de Compétences'));
        $this->set('json', $this->JsonTree->allLpcnodesToJson());
    }

    /**
     * @param int $id Node id to move
     * @return void
     */
    public function moveup($id = null)
    {
        $this->Lpcnodes->get($id);
        if (!$this->Lpcnode->exists()) {
            throw new NotFoundException(__('Ce noeud n\'existe pas ;)'));
        }

        $this->Lpcnode->moveUp($this->Competence->id, 1);
        $this->redirect(['action' => 'index']);
    }

    /**
     * @param int $id Node id to move
     * @return void
     */
    public function movedown($id = null)
    {
        $this->Lpcnode->id = $id;
        if (!$this->Lpcnode->exists()) {
            throw new NotFoundException(__('Ce noeud n\'existe pas ;)'));
        }

        $this->Lpcnode->moveDown($this->Lpcnode->id, 1);
        $this->redirect(['action' => 'index']);
    }

    /**
     * @param int $id Node id to delete
     * @return void
     */
    public function deleteNode($id = null)
    {
        $this->Lpcnode->id = $id;
        if (!$this->Lpcnode->exists()) {
            throw new NotFoundException(__('Ce noeud n\'existe pas ;)'));
        }

        $this->Lpcnode->delete();
        $this->redirect(['action' => 'index']);
    }

    /**
     * add method
     *
     * @param int $id Parent node if necessary
     * @return void
     */
    public function add($id = null)
    {
        $this->set('title_for_layout', __('Ajouter un noeud au Livret Personnel de Compétences'));
        if ($this->request->is('post')) {
            $this->Lpcnode->create();
            if ($this->Lpcnode->save($this->request->data)) {
                $this->Flash->success('La nouvelle compétence a été correctement ajoutée');
                if (isset($this->request->data['Lpcnode']['parent_id'])) {
                    $this->redirect(['action' => 'add', $this->request->data['Lpcnode']['parent_id']]);
                } else {
                    $this->redirect(['action' => 'index']);
                }
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }
        
        //On passe le paramètre à la vue
        if (isset($id) && is_numeric($id)) {
            $this->set('idnode', $id);
        }
        
        //Récupération des ids des catégories existantes
        $competenceids = $this->Lpcnodes->find('treeList');
        $this->set('cid', $competenceids);
    }

    /**
     * edit method
     *
     * @param int $id Node id to edit
     * @throws NotFoundException
     * @return void
     */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Modifier un noeud du Livret Personnel de Compétences'));

        $lpcnode = $this->Lpcnodes->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $lpcnode = $this->Lpcnodes->patchEntity($lpcnode, $this->request->data);
            if ($this->Lpcnodes->save($lpcnode)) {
                $this->Flash->success('Le noeud LPC a été correctement modifé');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        //Récupération des ids des catégories existantes
        $competenceids = $this->Lpcnodes->find('treeList');
        $this->set('cid', $competenceids);
        $this->set('lpcnode', $lpcnode);
    }
}
