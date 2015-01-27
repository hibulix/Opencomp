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
	public $actsAs = array('CustomTree', 'Containable');

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty')
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
		return $this->find('list',[
			'fields' => [
				'Competence.lft',
				'Competence.rght'
			],
			'conditions'=>[
				'Competence.id IN' => $ids_array
			]
		]);
	}

	/**
	 * Méthode permettant de retourner l'ensemble de la hiérarchie de compétences
	 * à partir des id_competence les plus profonds uniquements.
	 *
	 * @param array $ids_array Un tableau contentant les id_competence les plus profond dont on souhaite la hiérarchie.
	 * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
	public function findAllCompetencesFromCompetenceId($ids_array){
		$competence_bounds = $this->returnBoundsFromCompetenceId($ids_array);
		$sql_string = "SELECT * FROM competences AS Competence WHERE ";

		foreach($competence_bounds as $lft => $rght){
			$sql_string .= "(lft < $lft AND rght > $rght) OR ";
		}

		$sql_string = substr($sql_string,0,-3);
		$sql_string .= "ORDER BY lft;";

		$competences = $this->query($sql_string);

		return $this->formatCompetencesTheJstreeWay($competences);
	}

	/**
	 * Méthode permettant de retourner l'ensemble de l'arbre de compétences.
	 *
	 * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
	public function findAllCompetences(){
		$competences = $this->find('all',[
			'order' => ['Competence.lft'],
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
			$tab[$num]['text'] = $c['Competence']['title'];
			$tab[$num]['data']['type'] = "noeud";
			$tab[$num]['li_attr']['data-type'] = "noeud";
			$tab[$num]['li_attr']['data-id'] = $c['Competence']['id'];
		}

		return $tab;
	}

}
