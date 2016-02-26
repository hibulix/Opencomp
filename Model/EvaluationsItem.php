<?php
App::uses('AppModel', 'Model');
/**
 * EvaluationsItem Model
 *
 * @property Evaluation $Evaluation
 * @property Item $Item
 */
class EvaluationsItem extends AppModel {

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
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
		)
	);

    public function isItemAlreadyAttachedToEvaluation($evaluation_id, $item_id){
        return $this->find('first', array(
            'conditions' => array(
                'EvaluationsItem.evaluation_id' => $evaluation_id,
                'EvaluationsItem.item_id' => $item_id
            )
        ));
    }

    /**
	 * Cette fonction permet de renuméroter la position des items associés
	 * à une évaluation (par exemple après la dissociation d'un item).
	 * @param int $evaluation_id L'id de l'évaluation concerné par l'opération
	 * @param int $position À partir de quel position faut il renuméroter ?
	 * @return mixed 
	 */
    public function renumberItemsEvaluation($evaluation_id, $position){
		return $this->updateAll(
		    array('EvaluationsItem.position' => 'EvaluationsItem.position - 1'),
		    array('EvaluationsItem.evaluation_id' => $evaluation_id,
		    	  'EvaluationsItem.position >' => $position)
		);
	}
}
