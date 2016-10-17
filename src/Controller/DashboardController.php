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
}
