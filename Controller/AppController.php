<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');
App::uses('AuthComponent', 'Controller/Component');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array(
		'Session',
		'Auth' => array(
			'authenticate' => array('Form' => array('fields' => array('username' => 'email'))),
			'loginRedirect' => array('controller' => 'posts', 'action' => 'index'),
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login', 'login')
			)
		);

	public function beforeFilter()
	{
		$this->Auth->allow('login', 'view');
	}
}
