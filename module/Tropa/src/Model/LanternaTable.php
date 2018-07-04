<?php

namespace Tropa\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Select;

class LanternaTable {
	/**
	 *
	 * @var TableGatewayInterface
	 */
	private $tableGateway;
	/**
	 *
	 * @var string
	 */
	private $keyName = 'codigo';
	/**
	 *
	 * @param TableGatewayInterface $tableGateway
	 */
	public function __construct(TableGatewayInterface $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}
	/**
	 *
	 * @return ResultInterface
	 */
	public function fetchAll()
	{
		$select = new Select();
		$select->from('lanterna')
		->columns(array('codigo','nome'))
		->join(array('s'=>'setor'), 'lanterna.codigo_setor = s.codigo', array('setor'=>'nome'));
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
}

?>