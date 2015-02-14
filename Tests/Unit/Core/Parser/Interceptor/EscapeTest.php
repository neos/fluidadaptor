<?php
namespace TYPO3\Fluid\Tests\Unit\Core\Parser\Interceptor;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Tests\UnitTestCase;
use TYPO3\Fluid\Core\Parser\Interceptor\Escape;
use TYPO3\Fluid\Core\Parser\InterceptorInterface;
use TYPO3\Fluid\Core\Parser\ParsingState;
use TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Testcase for Interceptor\Escape
 */
class EscapeTest extends UnitTestCase {

	/**
	 * @var Escape|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $escapeInterceptor;

	/**
	 * @var AbstractViewHelper|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $mockViewHelper;

	/**
	 * @var ViewHelperNode|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $mockNode;

	/**
	 * @var ParsingState|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $mockParsingState;

	public function setUp() {
		$this->escapeInterceptor = $this->getAccessibleMock('TYPO3\Fluid\Core\Parser\Interceptor\Escape', array('dummy'));
		$this->mockViewHelper = $this->getMockBuilder('TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper')->disableOriginalConstructor()->getMock();
		$this->mockNode = $this->getMockBuilder('TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode')->disableOriginalConstructor()->getMock();
		$this->mockParsingState = $this->getMockBuilder('TYPO3\Fluid\Core\Parser\ParsingState')->disableOriginalConstructor()->getMock();
	}

	/**
	 * @test
	 */
	public function processDoesNotDisableEscapingInterceptorByDefault() {
		$interceptorPosition = InterceptorInterface::INTERCEPT_OPENING_VIEWHELPER;
		$this->mockViewHelper->expects($this->once())->method('isChildrenEscapingEnabled')->will($this->returnValue(TRUE));
		$this->mockNode->expects($this->once())->method('getUninitializedViewHelper')->will($this->returnValue($this->mockViewHelper));

		$this->assertTrue($this->escapeInterceptor->_get('childrenEscapingEnabled'));
		$this->escapeInterceptor->process($this->mockNode, $interceptorPosition, $this->mockParsingState);
		$this->assertTrue($this->escapeInterceptor->_get('childrenEscapingEnabled'));
	}

	/**
	 * @test
	 */
	public function processDisablesEscapingInterceptorIfViewHelperDisablesIt() {
		$interceptorPosition = InterceptorInterface::INTERCEPT_OPENING_VIEWHELPER;
		$this->mockViewHelper->expects($this->once())->method('isChildrenEscapingEnabled')->will($this->returnValue(FALSE));
		$this->mockNode->expects($this->once())->method('getUninitializedViewHelper')->will($this->returnValue($this->mockViewHelper));

		$this->assertTrue($this->escapeInterceptor->_get('childrenEscapingEnabled'));
		$this->escapeInterceptor->process($this->mockNode, $interceptorPosition, $this->mockParsingState);
		$this->assertFalse($this->escapeInterceptor->_get('childrenEscapingEnabled'));
	}

	/**
	 * @test
	 */
	public function processReenablesEscapingInterceptorOnClosingViewHelperTagIfItWasDisabledBefore() {
		$interceptorPosition = InterceptorInterface::INTERCEPT_CLOSING_VIEWHELPER;
		$this->mockViewHelper->expects($this->any())->method('isOutputEscapingEnabled')->will($this->returnValue(FALSE));
		$this->mockNode->expects($this->any())->method('getUninitializedViewHelper')->will($this->returnValue($this->mockViewHelper));

		$this->escapeInterceptor->_set('childrenEscapingEnabled', FALSE);
		$this->escapeInterceptor->_set('viewHelperNodesWhichDisableTheInterceptor', array($this->mockNode));

		$this->escapeInterceptor->process($this->mockNode, $interceptorPosition, $this->mockParsingState);
		$this->assertTrue($this->escapeInterceptor->_get('childrenEscapingEnabled'));
	}

	/**
	 * @test
	 */
	public function processWrapsCurrentViewHelperInHtmlspecialcharsViewHelperOnObjectAccessor() {
		$interceptorPosition = InterceptorInterface::INTERCEPT_OBJECTACCESSOR;
		$mockNode = $this->getMock('TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode', array(), array(), '', FALSE);
		$mockEscapeViewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper');
		$mockObjectManager = $this->getMock('TYPO3\Flow\Object\ObjectManagerInterface');
		$mockObjectManager->expects($this->at(0))->method('get')->with('TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper')->will($this->returnValue($mockEscapeViewHelper));
		$mockObjectManager->expects($this->at(1))->method('get')->with('TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', $mockEscapeViewHelper, array('value' => $mockNode))->will($this->returnValue($this->mockNode));
		$this->escapeInterceptor->injectObjectManager($mockObjectManager);

		$actualResult = $this->escapeInterceptor->process($mockNode, $interceptorPosition, $this->mockParsingState);
		$this->assertSame($this->mockNode, $actualResult);
	}
}
