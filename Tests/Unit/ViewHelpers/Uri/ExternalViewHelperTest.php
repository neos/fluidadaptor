<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Uri;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/../ViewHelperBaseTestcase.php');

/**
 * Testcase for the external uri view helper
 *
 */
class ExternalViewHelperTest extends \TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * var \TYPO3\Fluid\ViewHelpers\Uri\ExternalViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = new \TYPO3\Fluid\ViewHelpers\Uri\ExternalViewHelper();
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 */
	public function renderReturnsSpecifiedUri() {
		$this->viewHelper->initialize();
		$actualResult = $this->viewHelper->render('http://www.some-domain.tld');

		$this->assertEquals('http://www.some-domain.tld', $actualResult);
	}

	/**
	 * @test
	 */
	public function renderAddsHttpPrefixIfSpecifiedUriDoesNotContainScheme() {
		$this->viewHelper->initialize();
		$actualResult = $this->viewHelper->render('www.some-domain.tld');

		$this->assertEquals('http://www.some-domain.tld', $actualResult);
	}

	/**
	 * @test
	 */
	public function renderAddsSpecifiedSchemeIfUriDoesNotContainScheme() {
		$this->viewHelper->initialize();
		$actualResult = $this->viewHelper->render('some-domain.tld', 'ftp');

		$this->assertEquals('ftp://some-domain.tld', $actualResult);
	}

	/**
	 * @test
	 */
	public function renderDoesNotAddEmptyScheme() {
		$this->viewHelper->initialize();
		$actualResult = $this->viewHelper->render('some-domain.tld', '');

		$this->assertEquals('some-domain.tld', $actualResult);
	}
}


?>
