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
use Tester\DomQuery;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DomAssert extends BaseAssert
{

	/**
	 * @param $type
	 * @return $this
	 */
	public function has($type)
	{
		if (!$this->getObject()->has($type)) {
			Assert::fail("Selector '$type' does not exist.");
		}
		return $this;
	}


	/**
	 * @param $type
	 * @return $this
	 */
	public function xpathHas($type)
	{
		if (!$this->getObject()->xpath($type)) {
			Assert::fail("Selector '$type' does not exist.");
		}
		return $this;
	}


	/**
	 * @param $expected
	 * @param $type
	 * @return $this
	 */
	public function xpathHasCount($expected, $type)
	{
		$el = $this->getObject()->xpath($type);
		Assert::equal($expected, count($el));
		return $this;
	}


	/**
	 * @param $expected
	 * @param $type
	 * @return $this
	 */
	public function hasCount($expected, $type)
	{
		$el = $this->getObject()->find($type);
		Assert::equal($expected, count($el));
		return $this;
	}


	/**
	 * @param $expected
	 * @param $path
	 * @return $this
	 */
	public function contains($expected, $path)
	{
		$el = $this->getObject()->find($path);
		Assert::equal($expected, trim((string)$el[0]));
		return $this;
	}


	/**
	 * @param $expected
	 * @param $path
	 * @return $this
	 */
	public function xpathContains($expected, $path)
	{
		$el = $this->getObject()->xpath($path);
		Assert::equal($expected, trim((string)$el[0]));
		return $this;
	}


	/**
	 * @param $expected
	 * @param $selector
	 * @param $attribute
	 * @return $this
	 */
	public function xpathContainsAttribute($expected, $selector, $attribute)
	{
		if (!$el = $this->getObject()->xpath($selector)) {
			Assert::fail("Selector '$selector' does not exist.");
		}
		$attrs = (array)$el[0]->attributes();
		$attrs = $attrs['@attributes'];

		if (!isset($attrs[$attribute])) {
			Assert::fail("Attribute '$attribute' does not exist.");
		}

		Assert::equal($expected, $attrs[$attribute]);
		return $this;
	}


}
