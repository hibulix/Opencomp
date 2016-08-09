<?php
namespace app\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    App\Controller\AppController;

/**
 * Periods Controller
 *
 * @property Period $Period
 */
class PeriodsController extends AppController
{

    public function add()
    {
        if ($this->request->is('post')) {
            $period = $this->Periods->newEntity($this->request->data);
            if ($this->Periods->save($period)) {
                $this->Flash->success('La nouvelle période a été correctement ajoutée.');
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire.');
            }

            return $this->redirect(['controller' => 'Establishments', 'action' => 'view', $this->request->query['establishment_id']]);
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return \Cake\Network\Response|null
     */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Modifier une période'));
        $period = $this->Periods->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $period = $this->Periods->patchEntity($period, $this->request->data);
            if ($this->Periods->save($period)) {
                $this->Flash->success('La période a été correctement mise à jour');
                

                return $this->redirect(['controller' => 'Establishments', 'action' => 'view', $this->request->query['establishment_id']]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }
        $this->set(compact('period'));
    }
}
