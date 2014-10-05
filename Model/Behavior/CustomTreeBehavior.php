<?php

/**
 * Tree Behavior.
 *
 * Enables a model object to act as a node-based tree. Using Modified Preorder Tree Traversal
 *
 * @see http://en.wikipedia.org/wiki/Tree_traversal
 * @package       Cake.Model.Behavior
 * @link http://book.cakephp.org/2.0/en/core-libraries/behaviors/tree.html
 */
App::import('Behavior', 'Tree');

class CustomTreeBehavior extends TreeBehavior {

/**
 * A convenience method for returning a hierarchical array used for HTML select boxes
 *
 * @param Model $Model Model instance
 * @param string|array $conditions SQL conditions as a string or as an array('field' =>'value',...)
 * @param string $keyPath A string path to the key, i.e. "{n}.Post.id"
 * @param string $valuePath A string path to the value, i.e. "{n}.Post.title"
 * @param string $spacer The character or characters which will be repeated
 * @param integer $recursive The number of levels deep to fetch associated records
 * @return array An associative array of records, where the id is the key, and the display field is the value
 * @link http://book.cakephp.org/2.0/en/core-libraries/behaviors/tree.html#TreeBehavior::generateTreeList
 */
	public function generateTreeListWithDepth(Model $Model, $conditions = null, $keyPath = null, $valuePath = null, $spacer = '_', $recursive = null) {
		$overrideRecursive = $recursive;
		extract($this->settings[$Model->alias]);
		if (!is_null($overrideRecursive))
			$recursive = $overrideRecursive;

		if ($keyPath === null && $valuePath === null && $Model->hasField($Model->displayField)) {
			$fields = array($Model->primaryKey, $Model->displayField, $left, $right);
		} else {
			$fields = null;
		}

		if ($keyPath === null)
			$keyPath = '{n}.' . $Model->alias . '.' . $Model->primaryKey;

		if ($valuePath === null) {
			$valuePath = array('%s-%s', '{n}.depth', '{n}.' . $Model->alias . '.' . $Model->displayField);
		} elseif (is_string($valuePath)) {
			$valuePath = array('%s-%s', '{n}.depth', $valuePath);
		} else {
			$valuePath[0] = '{' . (count($valuePath) - 1) . '}' . $valuePath[0];
			$valuePath[] = '{n}.depth';
		}
		
		$order = $Model->alias . '.' . $left . ' asc';
		$results = $Model->find('all', compact('conditions', 'fields', 'order', 'recursive'));
		$stack = array();

		foreach ($results as $i => $result) {
			$count = count($stack);
			while ($stack && ($stack[$count - 1] < $result[$Model->alias][$right])) {
				array_pop($stack);
				$count--;
			}
			$results[$i]['depth'] = $count;
			$stack[] = $result[$Model->alias][$right];
		}
		if (empty($results)) {
			return array();
		}
		
		foreach ($results as $i => $result) {
			$resultOrdered[$result[$Model->alias][$Model->primaryKey]][$Model->primaryKey] = intval($result[$Model->alias][$Model->primaryKey]);
			$resultOrdered[$result[$Model->alias][$Model->primaryKey]][$Model->displayField] = $result[$Model->alias][$Model->displayField];
			$resultOrdered[$result[$Model->alias][$Model->primaryKey]]['depth'] = $result['depth'];
		}
		
		return array_values($resultOrdered);
	}
}