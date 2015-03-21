<?php
App::uses('AppController', 'Controller');
/**
 * Albums Controller
 *
 * @property Album $Album
 * @property PaginatorComponent $Paginator
 */
class AlbumsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Album->recursive = 0;
		$this->set('albums', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Album->exists($id)) {
			throw new NotFoundException(__('Invalid album'));
		}
		$options = array('conditions' => array('Album.' . $this->Album->primaryKey => $id));
		$this->set('album', $this->Album->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Album->create();
			if ($this->Album->save($this->request->data)) {
				$this->Session->setFlash(__('The album has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The album could not be saved. Please, try again.'));
			}
		}
		$users = $this->Album->User->find('list');
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Album->exists($id)) {
			throw new NotFoundException(__('Invalid album'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Album->save($this->request->data)) {
				$this->Session->setFlash(__('The album has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The album could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Album.' . $this->Album->primaryKey => $id));
			$this->request->data = $this->Album->find('first', $options);
		}
		$users = $this->Album->User->find('list');
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Album->id = $id;
		if (!$this->Album->exists()) {
			throw new NotFoundException(__('Invalid album'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Album->delete()) {
			$this->Session->setFlash(__('The album has been deleted.'));
		} else {
			$this->Session->setFlash(__('The album could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
