<?php
namespace App\View\Cell;

use App\Model\Table\ClassroomsTable;
use App\Model\Table\ReportsTable;
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
     * @param $classroom_id
     */
    public function stats($classroom_id)
    {
        $this->loadModel('Classrooms');
        $pupils = $this->Classrooms->ClassroomsPupils->find()
            ->where(['classroom_id' => $classroom_id])->count();
        $evaluations = $this->Classrooms->Evaluations->find()
            ->where(['unrated'=>0,'classroom_id'=>$classroom_id])
            ->count();
        $unrated_items = $this->Classrooms->Evaluations->EvaluationsCompetences->find()
            ->contain(['Evaluations'=> function ($q) use ($classroom_id) {
                return $q
                    ->where(['unrated'=>1,'classroom_id'=>$classroom_id]);
            }])->count();
        $reports = $this->Classrooms->Reports->find()
            ->where(['classroom_id'=>$classroom_id])
            ->count();
        $action = $this->request->params['action'];
        $this->set(compact('pupils','evaluations','unrated_items','reports','classroom_id','action'));
    }
}
