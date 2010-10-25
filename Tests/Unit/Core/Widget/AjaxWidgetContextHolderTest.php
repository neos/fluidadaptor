<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Tests\Unit\Core\Widget;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Testcase for AjaxWidgetContextHolder
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class AjaxWidgetContextHolderTest extends \F3\Testing\BaseTestCase {

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function storeSetsTheAjaxWidgetIdentifierInContextAndIncreasesIt() {
		$ajaxWidgetContextHolder = $this->getAccessibleMock('F3\Fluid\Core\Widget\AjaxWidgetContextHolder', array('dummy'));
		$ajaxWidgetContextHolder->_set('nextFreeAjaxWidgetId', 123);

		$widgetContext = $this->getMock('F3\Fluid\Core\Widget\WidgetContext', array('setAjaxWidgetIdentifier'));
		$widgetContext->expects($this->once())->method('setAjaxWidgetIdentifier')->with(123);

		$ajaxWidgetContextHolder->store($widgetContext);
		$this->assertEquals(124, $ajaxWidgetContextHolder->_get('nextFreeAjaxWidgetId'));
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function storedWidgetContextCanBeRetrievedAgain() {
		$ajaxWidgetContextHolder = $this->getAccessibleMock('F3\Fluid\Core\Widget\AjaxWidgetContextHolder', array('dummy'));
		$ajaxWidgetContextHolder->_set('nextFreeAjaxWidgetId', 123);

		$widgetContext = $this->getMock('F3\Fluid\Core\Widget\WidgetContext', array('setAjaxWidgetIdentifier'));
		$ajaxWidgetContextHolder->store($widgetContext);

		$this->assertSame($widgetContext, $ajaxWidgetContextHolder->get('123'));
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @expectedException F3\Fluid\Core\Widget\Exception\WidgetContextNotFoundException
	 */
	public function getThrowsExceptionIfWidgetContextIsNotFound() {
		$ajaxWidgetContextHolder = new \F3\Fluid\Core\Widget\AjaxWidgetContextHolder();
		$ajaxWidgetContextHolder->get(42);
	}
}
?>