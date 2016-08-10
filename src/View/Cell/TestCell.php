<?php
namespace App\View\Cell;

use App\Model\Table\EvaluationsTable;
use Cake\ORM\TableRegistry;
use Cake\View\Cell;

/**
 * Classroom cell
 */
class TestCell extends Cell
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
     * @param $evaluation_id
     */
    public function header($evaluation_id)
    {

        /** @var EvaluationsTable $evaluations */
        $evaluations = TableRegistry::get('Evaluations');
        $evaluation = $evaluations->get($evaluation_id, [
            'contain' => [
                'Users', 'Periods', 'Classrooms'
            ]
        ]);
        $levels_pupils = $evaluations->findPupilsByLevels($evaluation_id);
        $action = $this->request->params['action'];
        $this->set(compact('evaluation', 'levels_pupils', 'action'));
    }
}
