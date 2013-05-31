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

use Nette\Application\IPresenter;
use Nette\ArrayHash;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextInput;
use Nette\Utils\Arrays;
use Tester\Assert;
use Venne\Forms\Container;
use Venne\Tester\Helpers\DomHelper;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormAssert extends ComponentAssert
{

	/**
	 * @param $obj
	 * @return array
	 */
	protected function objectToArray($obj)
	{
		if (is_object($obj)) {
			$obj = (array)$obj;
		}
		if (is_array($obj)) {
			$new = array();
			foreach ($obj as $key => $val) {
				$new[$key] = $this->objectToArray($val);
			}
		} else $new = $obj;
		return $new;
	}


	/**
	 * @param $expected
	 * @return $this
	 */
	public function values($expected)
	{
		$values = $this->objectToArray($this->getObject()->getValues());
		$expected = $this->objectToArray($expected);
		Assert::equal($expected, $values);
		return $this;
	}


	/**
	 * @return $this
	 */
	public function valid()
	{
		Assert::true($this->getObject()->isValid());
		return $this;
	}


	/**
	 * @param $expected
	 * @param array $parent
	 * @return $this
	 */
	public function valuesInRender($expected, $parent = array())
	{
		foreach ($expected as $key => $val) {
			if (is_array($val)) {
				$p = $parent;
				$p[] = $key;
				$this->valuesInRender($val, $p);
			}

			$c = $this->getFormComponent($key, $parent);

			$dom = $this->getParent('Venne\\Tester\\Assertions\\ResponseAssert')->getTemplate()->getDom();

			if ($c instanceof TextInput) {
				$id = $c->getHtmlId();
				$dom->has('#' . $id);
				if ($c->control->type !== 'password') {
					$dom->xpathContainsAttribute($val, '//input[@id="' . $id . '"]', 'value');
				}
			} else if ($c instanceof SelectBox) {
				$id = $c->getHtmlId();
				$dom->has('#' . $id);
				if ($val !== NULL) {
					$dom->xpathContainsAttribute($val === TRUE ? '1' : $val, '//select[@id="' . $id . '"]/option[@selected]', 'value');
				} else {
					$dom->xpathHasCount(0, '//select[@id="' . $id . '"]/option[@selected]');
				}
			} else if ($c instanceof Checkbox) {
				$id = $c->getHtmlId();
				$dom->has('#' . $id);
				if ($val) {
					$dom->xpathContains('', '//input[@id="' . $id . '" and @checked]');
				} else {
					$dom->xpathHasCount(0, '//input[@id="' . $id . '" and @checked]');
				}
			}
		}
		return $this;
	}


	protected function getFormComponent($name, $parent = array())
	{
		$c = $this->getObject();
		foreach ($parent as $key) {
			$c = $c[$key];
		}
		$c = $c[$name];
		return $c;
	}


}
