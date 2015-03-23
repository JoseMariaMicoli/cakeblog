<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
class PostsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session', 'RequestHandler');

	public $helpers = array('Text');

/**
 * index method
 *
 * @return void
 */
	public function index() 
	{
		if ($this->RequestHandler->isRss() ) {
        	$posts = $this->Post->find(
            	'all',
            	array('limit' => 20, 'order' => 'Post.created DESC')
        	);
        	return $this->set(compact('posts'));
    	}

    	// this is not an Rss request, so deliver
    	// data used by website's interface
    	$this->paginate['Post'] = array(
        	'order' => 'Post.created DESC',
        	'limit' => 10
    	);

		$this->Post->recursive = 0;
		$this->set('posts', $this->Paginator->paginate());
		$posts = $this->paginate();
    	$this->set(compact('posts'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Post->exists($id)) {
			throw new NotFoundException(__('Invalid post'));
		}
		$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
		$this->set('post', $this->Post->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Post->create();
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('The post has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.'));
			}
		}
		$categories = $this->Post->Category->find('list');
		$comments = $this->Post->Comment->find('list');
		$users = $this->Post->User->find('list');
		$tags = $this->Post->Tag->find('list');
		$this->set(compact('categories', 'comments', 'users', 'tags'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Post->exists($id)) {
			throw new NotFoundException(__('Invalid post'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('The post has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
			$this->request->data = $this->Post->find('first', $options);
		}
		$categories = $this->Post->Category->find('list');
		$comments = $this->Post->Comment->find('list');
		$users = $this->Post->User->find('list');
		$tags = $this->Post->Tag->find('list');
		$this->set(compact('categories', 'comments', 'users', 'tags'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Post->id = $id;
		if (!$this->Post->exists()) {
			throw new NotFoundException(__('Invalid post'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Post->delete()) {
			$this->Session->setFlash(__('The post has been deleted.'));
		} else {
			$this->Session->setFlash(__('The post could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
