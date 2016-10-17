<?php

namespace App\Controller;
use Cake\ORM\TableRegistry;

/**
 * @property \App\Model\Table\RepositoriesTable Repositories
 */
class RepositoriesController extends AppController
{
    /**
     * List all repositories
     * @return void
     */
    public function index()
    {
        $repositories = $this->Repositories->find('all', [
            'conditions' => [
                'deleted' => 0
            ],
            'contain' => ['Legislations']
        ])->all();

        $this->set(compact('repositories'));
    }
    /**
     * @param null $id repo id
     * @return void
     */
    public function view($id)
    {
        $competences = TableRegistry::get('Competences');

        $list = $competences
            ->find('all')
            ->select(['id', 'parent_id', 'title', 'type', 'end_cycle'])
            ->where(['repository_id' => $id])->toArray();

        $listjson = array_map(function ($list) {
            if ($list['parent_id'] === null) {
                $list['parent_id'] = '#';
            }
            if ($list['end_cycle'] == 1) {
                $list['type'] = 'cycle';
            }


            return [
                'id' => strval($list['id']),
                'parent' => strval($list['parent_id']),
                'text' => $list['title'],
                'type' => strval($list['type'])
            ];
        }, $list);

        $this->set('listjson', $listjson);
        $this->set('listjson', json_encode($listjson));
        $this->set('_serialize', 'listjson');
    }

}
