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
use Nette\Application\IResponse;
use Tester\Assert;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ResponseAssert extends BaseAssert
{

	/** @var IPresenter */
	private $presenter;

	/** @var TemplateAssert */
	private $template;


	public function __construct(IResponse $object, IPresenter $presenter = NULL)
	{
		parent::__construct($object);
		$this->presenter = $presenter;
	}


	/**
	 * @param $type
	 * @return ResponseAssert
	 */
	public function type($type)
	{
		Assert::type($type, $this->getObject());
		return $this;
	}


	/**
	 * @return TemplateAssert
	 */
	public function getTemplate()
	{
		if (!$this->template) {
			$this->type('Nette\Application\Responses\TextResponse');
			$this->template = new TemplateAssert($this->getObject()->getSource());
			$this->template->setParent($this);
		}
		return $this->template;
	}


	/**
	 * @return ComponentAssert
	 */
	public function getPresenter()
	{
		$assert = new ComponentAssert($this->presenter);
		$assert->setParent($this);
		return $assert;
	}


	/**
	 * @param $expected
	 * @return $this
	 */
	public function redirectContains($expected)
	{
		Assert::contains($expected, $this->getObject()->url);
		return $this;
	}


}
