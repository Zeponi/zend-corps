<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tropa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tropa\Form\Lanterna as LanternaForm;
use Tropa\Model\Lanterna;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\I18n\Translator\Resources;
use Zend\Mvc\I18n\Translator as MvcTranslator;
use Zend\Validator\AbstractValidator;
use Zend\I18n\Translator\Translator;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class LanternaController extends AbstractActionController
{
    private $table;
    private $parentTable;

    public function __construct($table, $parentTable, $sessionManager)
    {
        $this->table = $table;
        $this->parentTable = $parentTable;
        $sessionManager->start();
    }

    public function indexAction()
    {
        $urlEdit = $this->url()->fromRoute('tropa', ['controller'=>'lanterna', 'action'=>'edit']);
        $urlHomepage = $this->url()->fromRoute('home');
        $urlDelete = $this->url()->fromRoute('tropa', ['controller'=>'lanterna', 'action'=>'delete']);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Lanterna());
        $pageAdapter = new DbSelect($this->table->getSelect(),$this->table->getSql(),$resultSet);
        $paginator = new Paginator($pageAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page',1));
        $paginator->setItemCountPerPage(4);

        return new ViewModel(
            [
                'controller' => 'lanterna',
                'models' => $this->table->fetchAll(),
                'paginator' => $paginator,
                'route' => 'tropa',
                'urlDelete' => $urlDelete,
                'urlEdit' => $urlEdit,
                'urlHomepage' => $urlHomepage
            ]
        );
    }

    /**
     * Action to add and change records
     */
    public function editAction()
    {
        $codigo = $this->params()->fromRoute('key', null);
        $lanterna = $this->table->getModel($codigo);
        $form = new LanternaForm('lanterna',['table' => $this->parentTable]);
        $form->get('submit')->setValue(
            empty($codigo) ? 'Cadastrar' : 'Alterar'
            );
        $sessionStorage = new SessionArrayStorage();
        if (isset($sessionStorage->model)){
            $setor->exchangeArray($sessionStorage->model->toArray());
            unset($sessionStorage->model);
            $form->setInputFilter($lanterna->getInputFilter());
            $this->initValidatorTranslator();
            $form->bind($lanterna);
            $form->isValid();
        } else {
            $form->bind($lanterna);
        }
        return [
            'form' => $form,
            'title' => empty($codigo) ? 'Incluir' : 'Alterar'
        ];
    }

    /**
     * Action to save a record
     */
    public function saveAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new LanternaForm('lanterna',['table'=>$this->parentTable]);
            $lanterna = new Lanterna();
            $form->setInputFilter($lanterna->getInputFilter());
            $post = $request->getPost();
            $form->setData($post);
            if (! $form->isValid()) {
                $sessionStorage = new SessionArrayStorage();
                $sessionStorage->model = $post;
                return $this->redirect()->toRoute('tropa', [
                    'action' => 'edit',
                    'controller' => 'lanterna'
                ]);
            }
            $lanterna->exchangeArray($form->getData());
            $this->table->saveModel($lanterna);
        }
        return $this->redirect()->toRoute('tropa', [
            'controller' => 'lanterna'
        ]);
    }

    /**
     * Action to remove records
     */
    public function deleteAction()
    {
        $codigo = $this->params()->fromRoute('key', null);
        $this->table->deleteModel($codigo);
        return $this->redirect()->toRoute(
            'tropa',
            ['controller'=>'lanterna']
            );
    }

    private function initValidatorTranslator()
    {
        $translator = new Translator();
        $mvcTranslator = new MvcTranslator($translator);
        $mvcTranslator->addTranslationFilePattern(
            'phparray',
            Resources::getBasePath(),
            Resources::getPatternForValidator()
        );
        AbstractValidator::setDefaultTranslator($mvcTranslator);

    }
}
