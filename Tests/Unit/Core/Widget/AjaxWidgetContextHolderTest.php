<?php
namespace TYPO3\Fluid\Tests\Unit\Core\Widget;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Testcase for AjaxWidgetContextHolder
 *
 */
class AjaxWidgetContextHolderTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function storeSetsTheAjaxWidgetIdentifierInContextAndIncreasesIt() {
		$ajaxWidgetContextHolder = $this->getAccessibleMock('TYPO3\Fluid\Core\Widget\AjaxWidgetContextHolder', array('dummy'));
		$ajaxWidgetContextHolder->_set('nextFreeAjaxWidgetId', 123);

		$widgetContext = $this->getMock('TYPO3\Fluid\Core\Widget\WidgetContext', array('setAjaxWidgetIdentifier'));
		$widgetContext->expects($this->once())->method('setAjaxWidgetIdentifier')->with(123);

		$ajaxWidgetContextHolder->store($widgetContext);
		$this->assertEquals(124, $ajaxWidgetContextHolder->_get('nextFreeAjaxWidgetId'));
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function storedWidgetContextCanBeRetrievedAgain() {
		$ajaxWidgetContextHolder = $this->getAccessibleMock('TYPO3\Fluid\Core\Widget\AjaxWidgetContextHolder', array('dummy'));
		$ajaxWidgetContextHolder->_set('nextFreeAjaxWidgetId', 123);

		$widgetContext = $this->getMock('TYPO3\Fluid\Core\Widget\WidgetContext', array('setAjaxWidgetIdentifier'));
		$ajaxWidgetContextHolder->store($widgetContext);

		$this->assertSame($widgetContext, $ajaxWidgetContextHolder->get('123'));
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @expectedException TYPO3\Fluid\Core\Widget\Exception\WidgetContextNotFoundException
	 */
	public function getThrowsExceptionIfWidgetContextIsNotFound() {
		$ajaxWidgetContextHolder = new \TYPO3\Fluid\Core\Widget\AjaxWidgetContextHolder();
		$ajaxWidgetContextHolder->get(42);
	}
}
?>