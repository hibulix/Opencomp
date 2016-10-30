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

        $this->Settings = TableRegistry::get('Settings');
        $currentYear = $this->Settings->find('all', ['conditions' => ['Settings.key' => 'currentYear']])->first();
        $currentYear = $currentYear->value;

        $classrooms = $classroomsUsers->find('list', [
            'keyField' => 'classroom.id',
            'valueField' => 'classroom.title',
            'groupField' => 'classroom.establishment.name'
        ])->contain(['Classrooms.Establishments'])
            ->where([
                'ClassroomsUsers.user_id' => $this->Auth->user('id'),
                'Classrooms.year_id' => $currentYear,
            ])->toArray();

        $this->set(compact('classrooms'));
    }
}
