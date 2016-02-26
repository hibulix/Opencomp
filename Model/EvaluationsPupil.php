<?php
App::uses('AppModel', 'Model');
/**
 * EvaluationsPupils Model
 *
 * @property Evaluation $Evaluation
 * @property Pupil $Pupil
 */
class EvaluationsPupil extends AppModel {

    public $actsAs = array('Containable');

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Evaluation' => array(
            'className' => 'Evaluation',
            'foreignKey' => 'evaluation_id',
        ),
        'Pupil' => array(
            'className' => 'Pupil',
            'foreignKey' => 'pupil_id',
        )
    );
}
