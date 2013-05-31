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
use Nette\Templating\ITemplate;
use Venne\Tester\Helpers\DomHelper;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class TemplateAssert extends BaseAssert
{

	/** @var DomAssert */
	private $dom;


	public function __construct(ITemplate $template)
	{
		parent::__construct($template);
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
	 * @return DomAssert
	 */
	public function getDom()
	{
		if (!$this->dom) {
			$dom = DomHelper::getDomFromXml((string)$this->getObject());
			$this->dom = new DomAssert($dom);
			$this->dom->setParent($this);
		}
		return $this->dom;
	}

}
