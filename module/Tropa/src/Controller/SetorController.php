<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Tropa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\View\Model\ViewModel;
use Tropa\Form\Setor as SetorForm;
use Tropa\Model\Setor;

class SetorController extends AbstractActionController {
	public function indexAction() {
		return new ViewModel ( [ 
				'models' => $this->table->fetchAll () 
		] );
	}
	/**
	 * Action to add and change records
	 */
	public function editAction() {
		$codigo = $this->params ()->fromRoute ( 'key', null );
		$setor = $this->table->getModel ( $codigo );
		$form = new SetorForm ();
		$form->get ( 'submit' )->setValue ( empty ( $codigo ) ? 'Cadastrar' : 'Alterar' );
		$sessionStorage = new SessionArrayStorage ();
		if (isset ( $sessionStorage->model )) {
			$setor->exchangeArray ( $sessionStorage->model->toArray () );
			unset ( $sessionStorage->model );
			$form->setInputFilter ( $setor->getInputFilter () );
		}
		$form->bind ( $setor );
		$form->isValid ();
		return [ 
				'form' => $form 
		];
	}
	/**
	 * Action to save a record
	 */
	public function saveAction() {
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form = new SetorForm ();
			$setor = new Setor ();
			$form->setInputFilter ( $setor->getInputFilter () );
			$post = $request->getPost ();
			$form->setData ( $post );
			if (! $form->isValid ()) {
				$sessionStorage = new SessionArrayStorage ();
				$sessionStorage->model = $post;
				return $this->redirect ()->toRoute ( 'tropa', [ 
						'action' => 'edit',
						'controller' => 'setor' 
				] );
			}
			$setor->exchangeArray ( $form->getData () );
			$this->table->saveModel ( $setor );
		}
		return $this->redirect ()->toRoute ( 'tropa', [ 
				'controller' => 'setor' 
		] );
	}
	/**
	 * Action to remove records
	 */
	public function deleteAction() {
		$codigo = $this->params ()->fromRoute ( 'key', null );
		$this->table->deleteModel ( $codigo );
		return $this->redirect ()->toRoute ( 'tropa', [ 
				'controller' => 'setor' 
		] );
	}
	private $table;
	public function __construct($table) {
		$this->table = $table;
	}
}
