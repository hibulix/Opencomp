<?php
namespace App\View\Cell;

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
     * @param int $evaluationId Evaluation identifier
     * @return void
     */
    public function header($evaluationId)
    {
        $evaluations = TableRegistry::get('Evaluations');
        $evaluation = $evaluations->get($evaluationId, [
            'contain' => [
                'Users', 'Periods', 'Classrooms'
            ]
        ]);
        $levelsPupils = $evaluations->findPupilsByLevels($evaluationId);
        $action = $this->request->params['action'];
        $this->set(compact('evaluation', 'levelsPupils', 'action'));
    }
}
