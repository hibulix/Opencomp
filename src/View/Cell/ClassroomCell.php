<?php
namespace App\View\Cell;

use Cake\ORM\Query;
use Cake\View\Cell;

/**
 * Classroom cell
 *
 * @property ClassroomsTable Classrooms
 * @property ReportsTable Reports
 */
class ClassroomCell extends Cell
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
     * @param int $classroomId Classroom identifier
     * @return void
     */
    public function stats($classroomId)
    {
        $this->loadModel('Classrooms');
        $pupils = $this->Classrooms->ClassroomsPupils->find()
            ->where(['classroom_id' => $classroomId])->count();
        $evaluations = $this->Classrooms->Evaluations->find()
            ->where(['unrated' => 0, 'classroom_id' => $classroomId])
            ->count();
        $unratedItems = $this->Classrooms->Evaluations->EvaluationsCompetences->find()
            ->contain(['Evaluations' => function (Query $q) use ($classroomId) {
                return $q
                    ->where(['unrated' => 1, 'classroom_id' => $classroomId]);
            }])->count();
        $reports = $this->Classrooms->Reports->find()
            ->where(['classroom_id' => $classroomId])
            ->count();
        $action = $this->request->params['action'];
        $this->set(compact('pupils', 'evaluations', 'unratedItems', 'reports', 'classroomId', 'action'));
    }
}
