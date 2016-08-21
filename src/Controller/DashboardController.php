<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class DashboardController extends AppController
{

    /**
     * @return void
     */
    public function index()
    {
        $classroomsUsers = TableRegistry::get('ClassroomsUsers');
        $classrooms = $classroomsUsers->find('list', [
            'keyField' => 'classroom.id',
            'valueField' => 'classroom.title',
            'groupField' => 'classroom.establishment.name'
        ])->contain(['Classrooms.Establishments'])
            ->where(['ClassroomsUsers.user_id' => $this->Auth->user('id')])->toArray();

        $this->set(compact('classrooms'));
    }

    /**
     * @param null $id repo id
     * @return void
     */
    public function repo($id)
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
