<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Venne\Tester;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @property Helper $helper
 */
class TestCase extends \Tester\TestCase
{


	/** @var Helper */
	private $helper;


	/**
	 * @param Helper $helper
	 */
	public function setHelper($helper)
	{
		$this->helper = $helper;
	}


	/**
	 * @return Helper
	 */
	public function getHelper()
	{
		if (!$this->helper) {
			$this->helper = new Helper;
		}
		return $this->helper;
	}


	public function __get($val)
	{
		if ($val === 'helper') {
			return $this->getHelper();
		}
	}


}
