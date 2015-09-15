<?php
App::uses('AppModel', 'Model');
/**
 * Competence Model
 *
 * @property Competence $ParentCompetence
 * @property Competence $ChildCompetence
 * @property Item $Item
 */
class Competence extends AppModel {

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'title';

	/**
	 * Attached behaviours
	 *
	 * @var string
	 */
	public $actsAs = array('Tree', 'Containable');

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank')
			),
		),
	);

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'ParentCompetence' => array(
			'className' => 'Competence',
			'foreignKey' => 'parent_id'
		)
	);

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'ChildCompetence' => array(
			'className' => 'Competence',
			'foreignKey' => 'parent_id',
			'dependent' => false
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'competence_id',
			'dependent' => false
		)
	);

	/**
	 * Méthode intermédiaire permettant de retourner les bornes gauches et droites
	 * d'une compétence à partir son identifiant primaire.
	 *
	 * @param array $ids_array Un tableau contentant les id_competence dont on souhaite récupérer les bornes.
	 * @return array|null Un tableau avec en clés les bornes gauches et en valeurs les bornes droites.
     */
	private function returnBoundsFromCompetenceId($ids_array){
		if(is_array($ids_array)){
			return $this->find('list',[
					'fields' => [
							'Competence.lft',
							'Competence.rght'
					],
					'conditions'=>[
							'Competence.id IN' => $ids_array
					]
			]);
		}else{
			return $this->find('list',[
					'fields' => [
							'Competence.lft',
							'Competence.rght'
					],
					'conditions'=>[
							'Competence.id' => $ids_array
					]
			]);
		}

	}

	/**
	 * Méthode permettant de retourner l'ensemble de la hiérarchie de compétences
	 * à partir des id_competence les plus profonds uniquements.
	 *
	 * @param array $ids_array Un tableau contentant les id_competence les plus profond dont on souhaite la hiérarchie.
	 * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
	public function findAllCompetencesFromCompetenceId($ids_array, $format = 'jstree'){
		$competence_bounds = $this->returnBoundsFromCompetenceId($ids_array);
		$sql_string = "SELECT * FROM competences AS Competence WHERE ";

        if(is_array($ids_array)){
            $id_competences = implode(',',$ids_array);
            $sql_string .= "id IN ( $id_competences ) OR ";
        }else{
            $sql_string .= "id = $ids_array OR ";
        }
        
		foreach($competence_bounds as $lft => $rght){
			$sql_string .= "(lft < $lft AND rght > $rght) OR ";
		}

		$sql_string = substr($sql_string,0,-3);
        if($this->Auth->role == "admin")
            $sql_string .= "AND deleted = false ";
		$sql_string .= "ORDER BY lft;";

		$competences = $this->query($sql_string);
		if($format == 'jstree')
			return $this->formatCompetencesTheJstreeWay($competences);
		else
			return $this->formatTreeHelperWay($competences);
	}

	/**
	 * Méthode permettant de retourner l'ensemble de l'arbre de compétences.
	 *
	 * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
	public function findAllCompetences(){

        $conditions = [];
        if(AuthComponent::user('role') !== 'admin')
            $conditions['Competence.deleted'] = '0';

		$competences = $this->find('all',[
			'order' => ['Competence.lft'],
            'conditions' => $conditions,
			'recursive' => -1
		]);

		return $this->formatCompetencesTheJstreeWay($competences);
	}

	/**
	 * Méthode permettant de formatter un tableau en vue de son passage à JsTree.
	 *
	 * @param array $dataset Un resultset CakePHP contenant plusieurs tableaux Competence.
	 * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
	private function formatCompetencesTheJstreeWay($dataset){
		$tab = array();

		foreach($dataset as $num=>$c){
			$tab[$num]['id'] = $c['Competence']['id'];
			if($c['Competence']['parent_id'])
				$tab[$num]['parent'] = $c['Competence']['parent_id'];
			else
				$tab[$num]['parent'] = "#";
			$tab[$num]['icon'] = 'fa fa-lg fa-cubes';

			if($c['Competence']['deleted']){
				$tab[$num]['a_attr']['style'] = 'color:#cacaca; text-decoration:line-through;';
                $tab[$num]['data']['deleted'] = "true";
			}

			$tab[$num]['text'] = $c['Competence']['title'];
			$tab[$num]['data']['type'] = "noeud";
			$tab[$num]['li_attr']['data-type'] = "noeud";
			$tab[$num]['li_attr']['data-id'] = $c['Competence']['id'];
		}

		return $tab;
	}

	/**
	 * Méthode permettant de formatter un tableau en émulant le renvoie de la méthode GenerateTreeListWithDepth.
	 *
	 * @param array $dataset Un resultset CakePHP contenant plusieurs tableaux Competence.
	 * @return array Un tableau en émulant le format de renvoie de la méthode GenerateTreeListWithDepth
	 */
	private function formatTreeHelperWay($dataset){
		$tab = array();

		foreach($dataset as $num=>$c){
			$tab[$num]['id'] = $c['Competence']['id'];
			$tab[$num]['title'] = $c['Competence']['title'];
			$tab[$num]['depth'] = $c['Competence']['depth'];
		}

		return $tab;
	}

	/**
	 * Cette méthode de rappel permet de recalculer la profondeur de chaque item de l'arbre
	 * lorsqu'un item y est ajouté ou modifié.
	 *
	 */
	public function afterSave($created, $options = array()){
		$this->query('UPDATE competences SET competences.depth = DEPTH(competences.lft,competences.rght)');
	}

}
