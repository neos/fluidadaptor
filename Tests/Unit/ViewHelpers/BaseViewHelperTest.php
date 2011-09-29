<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/ViewHelperBaseTestcase.php');
/**
 */
class BaseViewHelperTest extends \TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase {
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderTakesBaseUriFromControllerContext() {
		$baseUri = 'http://typo3.org/';

		$this->request->expects($this->any())->method('getBaseUri')->will($this->returnValue($baseUri));

		$viewHelper = new \TYPO3\Fluid\ViewHelpers\BaseViewHelper();
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$expectedResult = '<base href="' . $baseUri . '" />';
		$actualResult = $viewHelper->render();
		$this->assertSame($expectedResult, $actualResult);
	}
}
?>