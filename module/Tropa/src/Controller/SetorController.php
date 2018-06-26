<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tropa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SetorController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(
        	['models' => $this->table->fetchAll()]
        );
    }
    /**
     * Action to add and change records
    */
    public function editAction()
    {
        
    }
    /**
     * Action to save a record
     */
    public function saveAction()
    {
        
    }
    /**
     * Action to remove records
     */
    public function deleteAction()
    {
        
    }
    
    private $table;
    
    public function __construct($table)
    {
    	$this->table = $table;
    }
}
