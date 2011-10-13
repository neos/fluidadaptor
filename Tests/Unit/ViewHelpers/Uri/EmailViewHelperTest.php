<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Uri;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/../ViewHelperBaseTestcase.php');

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Testcase for the email uri view helper
 *
 * @FLOW3\Scope("prototype")
 */
class EmailViewHelperTest extends \TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * var \TYPO3\Fluid\ViewHelpers\Uri\EmailViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = new \TYPO3\Fluid\ViewHelpers\Uri\EmailViewHelper();
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderPrependsEmailWithMailto() {
		$this->viewHelper->initialize();
		$actualResult = $this->viewHelper->render('some@email.tld');

		$this->assertEquals('mailto:some@email.tld', $actualResult);
	}
}


?>
