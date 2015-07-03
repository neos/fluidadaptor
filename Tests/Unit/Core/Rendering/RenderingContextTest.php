<?php
namespace TYPO3\Fluid\Tests\Unit\Core\Rendering;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Testcase for ParsingState
 *
 */
class RenderingContextTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * Parsing state
	 * @var \TYPO3\Fluid\Core\Rendering\RenderingContextInterface
	 */
	protected $renderingContext;

	public function setUp() {
		$this->renderingContext = new \TYPO3\Fluid\Core\Rendering\RenderingContext();
	}

	/**
	 * @test
	 */
	public function templateVariableContainerCanBeReadCorrectly() {
		$templateVariableContainer = $this->getMock(\TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer::class);
		$this->renderingContext->injectTemplateVariableContainer($templateVariableContainer);
		$this->assertSame($this->renderingContext->getTemplateVariableContainer(), $templateVariableContainer, 'Template Variable Container could not be read out again.');
	}

	/**
	 * @test
	 */
	public function controllerContextCanBeReadCorrectly() {
		$controllerContext = $this->getMock(\TYPO3\Flow\Mvc\Controller\ControllerContext::class, array(), array(), '', FALSE);
		$this->renderingContext->setControllerContext($controllerContext);
		$this->assertSame($this->renderingContext->getControllerContext(), $controllerContext);
	}

	/**
	 * @test
	 */
	public function viewHelperVariableContainerCanBeReadCorrectly() {
		$viewHelperVariableContainer = $this->getMock(\TYPO3\Fluid\Core\ViewHelper\ViewHelperVariableContainer::class);
		$this->renderingContext->injectViewHelperVariableContainer($viewHelperVariableContainer);
		$this->assertSame($viewHelperVariableContainer, $this->renderingContext->getViewHelperVariableContainer());
	}
}
