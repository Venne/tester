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

use Venne\Tester\Assertions\ResponseAssert;
use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Tester\DomQuery;
use Venne\Config\Configurator;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Helper
{

	/** @var Container|\SystemContainer */
	private $container;

	/** @var IPresenterFactory */
	private $presenterFactory;

	/** @var int */
	private $containerCounter = 0;

	/** @var int */
	private $environmentCounter = 0;

	/** @var string */
	private $sandboxDir;

	/** @var Configurator */
	private $configurator;


	/**
	 * @param string $sandboxDir
	 */
	public function setSandboxDir($sandboxDir)
	{
		$this->sandboxDir = $sandboxDir;
	}


	/**
	 * @return string
	 */
	public function getSandboxDir()
	{
		if (!$this->sandboxDir) {
			$this->sandboxDir = dirname(dirname(TEMP_DIR));
		}
		return $this->sandboxDir;
	}


	/**
	 * @return Container|\SystemContainer
	 */
	public function getContainer()
	{
		if (!$this->container) {
			$this->container = $this->getConfigurator()->createContainer();
		}
		return $this->container;
	}


	/**
	 * @return Configurator
	 */
	public function getConfigurator()
	{
		if (!$this->configurator) {
			$this->configurator = new Configurator($this->getSandboxDir(), getLoader());
		}
		return $this->configurator;
	}


	/**
	 * @return IPresenterFactory
	 */
	protected function getPresenterFactory()
	{
		if (!$this->presenterFactory) {
			$this->presenterFactory = $this->getContainer()->getByType('Nette\Application\IPresenterFactory');
		}
		return $this->presenterFactory;
	}


	/**
	 * @param $presenter
	 * @param $method
	 * @param array $params
	 * @param array $post
	 * @param array $files
	 * @param array $flags
	 * @return ResponseAssert
	 */
	public function createResponse($presenter, $method, array $params = array(), array $post = array(), array $files = array(), array $flags = array())
	{
		$request = new \Nette\Application\Request($presenter, $method, $params, $post, $files, $flags);
		$presenter = $this->getPresenterFactory()->createPresenter($presenter);
		$presenter->autoCanonicalize = FALSE;
		$response = $presenter->run($request);
		return new ResponseAssert($response, $presenter);
	}


	public function reloadContainer()
	{
		$this->presenterFactory = NULL;

		$container = $this->getContainer();
		$configurator = $this->getConfigurator();

		$class = $container->parameters['container']['class'] . '_test_' . $this->containerCounter++;
		\Nette\Utils\LimitedScope::evaluate($configurator->buildContainer($dependencies, $class));

		$this->container = new $class;
		$this->container->initialize();
		$this->container->addService('configurator', $configurator);
	}


	public function prepareTestEnvironment()
	{
		if (!is_dir(TEMP_DIR . '/environments')) {
			mkdir(TEMP_DIR . '/environments');
		}

		$target = TEMP_DIR . '/environments/' . $this->environmentCounter++;
		mkdir($target);

		copy(__DIR__ . '/env/sandbox.php', $target . '/sandbox.php');
		$c = include $target . '/sandbox.php';
		foreach ($c as $path) {
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
		}
		copy(dirname(dirname(TEMP_DIR)) . '/config/config.neon', $c['configDir'] . '/config.neon');
		copy(dirname(dirname(TEMP_DIR)) . '/config/settings.php', $c['configDir'] . '/settings.php');
		copy(dirname(dirname(TEMP_DIR)) . '/temp/database.db', $c['tempDir'] . '/database.db');

		$configurator = $this->getConfigurator();
		$configurator->addParameters($c);

		$parameters = include $c['configDir'] . '/settings.php';
		foreach ($parameters['modules'] as &$module) {
			$module['path'] = \Nette\DI\Helpers::expand($module['path'], $c + $this->getContainer()->parameters);
		}
		$configurator->addParameters($parameters);

		mkdir($c['tempDir'] . '/cache');

		if ($this->container) {
			$this->reloadContainer();
		}
	}


}
