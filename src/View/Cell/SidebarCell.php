<?php
namespace App\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

/**
 * Classroom cell
 */
class SidebarCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @param int $userId user identifier
     * @param int $currentClassroom Current classroom id
     * @return void
     */
    public function establishmentsUsers($userId, $currentClassroom)
    {
        $establishementUsers = TableRegistry::get('EstablishmentsUsers');
        $establishments = $establishementUsers->getUsersEstablishments($userId);

        $settingsTable = TableRegistry::get('Settings');
        $currentYear = $settingsTable->find('all', ['conditions' => ['Settings.key' => 'currentYear']])->first();

        $classroomsUsers = TableRegistry::get('ClassroomsUsers');
        $classrooms = $classroomsUsers->find('list', [
            'keyField' => 'classroom.id',
            'valueField' => 'classroom.title',
            'groupField' => 'classroom.establishment_id'
        ])->contain(['Classrooms'])
            ->where([
                'Classrooms.year_id' => $currentYear->value,
                'ClassroomsUsers.user_id' => $userId
            ])->toArray();
        $this->set(compact('establishments', 'classrooms', 'currentClassroom'));
        $this->set('params', $this->request->params);
    }
}
