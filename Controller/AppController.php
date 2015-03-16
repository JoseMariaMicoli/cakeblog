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
App::import('Component','Auth'); 

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
		'Auth'
		);

	public $helpers = array(
		'Session',
		'Form'
		);

	public function beforeFilter()
	{
		$this->Auth->authenticate = array(
			AuthComponent::ALL => array(
				'UserModel' => 'User',
				'fields' => array(
					'username' => 'email',
					'password' => 'password'
					)
				),
			'Form',
			);

		$this->Auth->authorize = "Controller";

		$this->Auth->loginAction = array(
			'plugin' => null,
			'controller' => 'users',
			'action' => 'login'
			);

		$this->Auth->logoutRedirect = array(
			'plugin'=>null,
			'controller'=>'users',
			'action'=>'login'
		);

		$this->Auth->loginRedirect = array(
			'plugin'=>null,
			'controller'=>'posts',
			'action'=>'index'
		);

		$this->Auth->error=__('Erro , você não logou!');

		$this->Auth->allowedActions = array('add','resetpassword','login');
	}

	/**public function isAuthorized($user)
	{
		if(!empty($this->request->params['admin'])) {
			return $user['role'] == 'admin';
		}
		return !empty($user);
	}	**/

	public function isAuthorized($user) 
	{
    	// Admin can access every action
    	if (isset($user['role']) && $user['role'] === 'admin') {
        	return true;
    	}

    	// Default deny
    	return false;
	}
}

