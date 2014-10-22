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
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Evaluation' => array(
            'className' => 'Evaluation',
            'foreignKey' => 'evaluation_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Pupil' => array(
            'className' => 'Pupil',
            'foreignKey' => 'pupil_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
