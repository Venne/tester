<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Venne\Tester\Assertions;

use Venne\Tester\IAssert;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class BaseAssert implements IAssert
{

	/** @var IAssert */
	private $parent;

	/** @var mixed */
	private $object;


	/**
	 * @param IAssert $parent
	 */
	public function __construct($object = NULL)
	{
		$this->object = $object;
	}


	/**
	 * @param IAssert $parent
	 * @return $this
	 */
	public function setParent(IAssert $parent)
	{
		$this->parent = $parent;
		return $this;
	}


	/**
	 * @return IAssert
	 */
	public function getParent($type = NULL)
	{
		$ret = $this->parent;
		if ($type) {
			while (!is_a($ret, $type)) {
				$ret = $ret->getParent();
				if ($ret === NULL) {
					break;
				}
			}
		}
		return $ret;
	}


	/**
	 * @param $object
	 * @return $this
	 */
	public function setObject($object)
	{
		$this->object = $object;
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getObject()
	{
		return $this->object;
	}


}
