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

use Tester\Assert;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ComponentAssert extends BaseAssert
{

	/**
	 * @param $name
	 * @return ComponentAssert
	 */
	public function getComponent($name)
	{
		$c = $this->getObject();
		$assert = new self($c[$name]);
		$assert->setParent($this);
		return $assert;
	}


	/**
	 * @param $name
	 * @return FormAssert
	 */
	public function getForm($name)
	{
		$c = $this->getObject();
		$assert = new FormAssert($c[$name]);
		$assert->setParent($this);
		return $assert;
	}


	/**
	 * @param $type
	 * @return $this
	 */
	public function type($type)
	{
		Assert::type($type, $this->getObject());
		return $this;
	}


	/**
	 * @param $name
	 * @return $this
	 */
	public function hasComponent($name)
	{
		$c = $this->getObject();
		Assert::type('Nette\ComponentModel\IComponent', $c[$name]);
		return $this;
	}


}
