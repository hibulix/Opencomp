<?php
App::uses('AppModel', 'Model');
/**
 * Lpcnode Model
 *
 * @property Lpcnode $ParentLpcnode
 * @property Item $Item
 * @property Lpcnode $ChildLpcnode
 *
 * @method int generateTreeList() generateTreeList(Model $Model, $conditions = null, $keyPath = null, $valuePath = null, $spacer = '_', $recursive = null) multiply two integers
 * @method void contain() contain($Model)
 */
class Lpcnode extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	public $actsAs = array('Tree', 'Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'lft' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'rght' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		)
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ParentLpcnode' => array(
			'className' => 'Lpcnode',
			'foreignKey' => 'parent_id',
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'lpcnode_id',
			'dependent' => false,
		),
		'ChildLpcnode' => array(
			'className' => 'Lpcnode',
			'foreignKey' => 'parent_id',
			'dependent' => false,
		)
	);

    public function findAllNodesWithParentId($id){
        return $this->find('all',
            array(
                'contain' => array('ChildLpcnode.id', 'Item.type = 1'),
                'conditions' => array('Lpcnode.parent_id'=>$id),
                'order' => 'Lpcnode.lft ASC',
            )
        );
    }

	public function findAllLpcnodeIn($ids_array = null){

		if(isset($ids_array) && is_array($ids_array))
			$conditions['Lpcnode.id'] = $ids_array;
		else
			$conditions = array();

		$this->contain('ChildLpcnode');
		$lpcnodes = $this->find('all',[
			'conditions' => [
				$conditions
			],
			'order' => ['Lpcnode.lft'],
			'recursive' => -1
		]);

		$tab = array();

		foreach($lpcnodes as $num=>$node){
			$tab[$num]['id'] = $node['Lpcnode']['id'];
			if($node['Lpcnode']['parent_id'])
				$tab[$num]['parent'] = $node['Lpcnode']['parent_id'];
			else
				$tab[$num]['parent'] = "#";
			$tab[$num]['text'] = $node['Lpcnode']['title'];
			$tab[$num]['li_attr']['data-id'] = $node['Lpcnode']['id'];

			if(count($node['ChildLpcnode'])){
				$tab[$num]['icon'] = 'fa fa-lg fa-cubes';
				$tab[$num]['data']['type'] = "noeud";
				$tab[$num]['li_attr']['data-type'] = "noeud";
			}else{
				$tab[$num]['icon'] = 'fa fa-lg fa-cube text-danger';
				$tab[$num]['data']['type'] = "feuille";
				$tab[$num]['li_attr']['data-type'] = "feuille";
			}
		}

		return $tab;
	}

	public function getLpcnodeIdsFromLivrEvalIds($livreval_ids, $palier){
		$matching = $this->find('list', [
			'fields' => ['livreval_id','id'],
			'conditions' => [
				'livreval_id IN' => $livreval_ids,
				'palier' => $palier
			]
		]);

		return $matching;
	}

	public function getLPCforPupilId($pupil_id, $palier){

		$lpc = $this->find('all', array(
			'conditions' => array(
				'Lpcnode.palier' => $palier,
				'Lpcnode.parent_id IS NOT NULL'
			),
			'recursive' => -1,
			'fields' => array('Lpcnode.title','Lpcnode.type','LpcnodesPupil.validation_date','LpcnodesPupil.type_val','Lpcnode.id'),
			'joins' => array(
				array('table' => 'lpcnodes_pupils',
					'alias' => 'LpcnodesPupil',
					'type' => 'LEFT',
					'conditions' => array(
						'Lpcnode.id = LpcnodesPupil.lpcnode_id',
						'LpcnodesPupil.pupil_id' => $pupil_id,
					),
				)
			),
			'order' => array(
				'Lpcnode.lft'
			)
		));

		return $lpc;
	}

	public function getPDFLPCforPupilId($pupil_id, $palier, $type){

		$lpc = $this->find('all', array(
			'conditions' => array(
				'Lpcnode.palier' => $palier,
				'Lpcnode.parent_id IS NOT NULL',
				'Lpcnode.type IN' => $type
			),
			'recursive' => -1,
			'fields' => array(
				'Lpcnode.id',
				'Lpcnode.page',
				'Lpcnode.X',
				'Lpcnode.Y',
				'LpcnodesPupil.validation_date'
			),
			'joins' => array(
				array('table' => 'lpcnodes_pupils',
					'alias' => 'LpcnodesPupil',
					'type' => 'INNER',
					'conditions' => array(
						'Lpcnode.id = LpcnodesPupil.lpcnode_id',
						'LpcnodesPupil.pupil_id' => $pupil_id,
					),
				)
			),
			'order' => array(
				'Lpcnode.lft'
			)
		));

		return $lpc;
	}

	private function getNbItemForCompetence($lpcnode_id){
		$competence = $this->find('first', array(
			'conditions' => array(
				'id' => $lpcnode_id
			),
			'recursive' => -1
		));

		$nb = $this->find('count', array(
			'conditions' => array(
				'type' => 4,
				'lft >' => $competence['Lpcnode']['lft'],
				'rght <' => $competence['Lpcnode']['rght']
			),
			'recursive' => -1
		));

		return $nb;
	}

	private function getValidatedItemsForCompetence($lpcnode_id, $pupil_id){
        $competence = $this->find('first', array(
            'conditions' => array(
                'id' => $lpcnode_id
            ),
            'recursive' => -1
        ));

        $nb = $this->find('count', array(
            'conditions' => array(
                'type' => 4,
                'lft >' => $competence['Lpcnode']['lft'],
                'rght <' => $competence['Lpcnode']['rght']
            ),
            'joins' => array(
                array('table' => 'lpcnodes_pupils',
                    'alias' => 'LpcnodesPupil',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Lpcnode.id = LpcnodesPupil.lpcnode_id',
                        'LpcnodesPupil.pupil_id' => $pupil_id,
                    ),
                )
            ),
            'recursive' => -1
        ));

        return $nb;
	}

	public function getCompetenceValidationPercentage($lpcnode_id, $pupil_id){
	    $nb_items = $this->getNbItemForCompetence($lpcnode_id);
        $nb_items_validated = $this->getValidatedItemsForCompetence($lpcnode_id, $pupil_id);
        $percent = $nb_items_validated * 100 / $nb_items;

        return round($percent);
    }
}
