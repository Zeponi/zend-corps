<?php

namespace Tropa\Model;

use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory;

class Setor 
{
	/**
	 *
	 * @var integer
	 */
	public $codigo;
	/**
	 *
	 * @var string
	 */
	public $nome;
	/**
	 *
	 * @var InputFilterInterface
	 */
	private $inputFilter;
	/**
	 *
	 * @param array $data
	 */
	public function exchangeArray(array $data)
	{
		foreach($data as $attribute => $value){
			$this->$attribute = $value;
		}
	}
}

?>