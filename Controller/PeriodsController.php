<?php
App::uses('AppController', 'Controller');
/**
 * Periods Controller
 *
 * @property Period $Period
 */
class PeriodsController extends AppController {

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Period->create();
			if ($this->Period->save($this->request->data)) {
				$this->Session->setFlash(__('La nouvelle période a été correctement ajoutée.'), 'flash_success');
				$this->redirect(array(
				    'controller'    => 'establishments',
				    'action'        => 'view', $this->request->data['Period']['establishment_id']));
			} else {
				$this->Session->setFlash(__('The period could not be saved. Please, try again.'));
			}
		}
		$years = $this->Period->Year->find('list');
		$establishments = $this->Period->Establishment->find('list');
		$this->set(compact('years', 'establishments'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Period->id = $id;
		if (!$this->Period->exists()) {
			throw new NotFoundException(__('Invalid period'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Period->save($this->request->data)) {
				$this->Session->setFlash(__('The period has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The period could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Period->read(null, $id);
		}
		$years = $this->Period->Year->find('list');
		$establishments = $this->Period->Establishment->find('list');
		$this->set(compact('years', 'establishments'));
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Period->id = $id;
		if (!$this->Period->exists()) {
			throw new NotFoundException(__('Invalid period'));
		}
		if ($this->Period->delete()) {
			$this->Session->setFlash(__('Period deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Period was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
