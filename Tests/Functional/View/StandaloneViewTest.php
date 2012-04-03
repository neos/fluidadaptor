<?php
namespace TYPO3\Fluid\Tests\Functional\View;

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
 * Testcase for Standalone View
 */
class StandaloneViewTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {

	/**
	 * @var string
	 */
	protected $standaloneViewNonce = '42';

	/**
	 * Every testcase should run *twice*. First, it is run in *uncached* way, second,
	 * it is run *cached*. To make sure that the first run is always uncached, the
	 * $standaloneViewNonce is initialized to some random value which is used inside
	 * an overridden version of StandaloneView::createIdentifierForFile.
	 */
	public function runBare() {
		$this->standaloneViewNonce = uniqid();
		parent::runBare();
		$numberOfAssertions = $this->getNumAssertions();
		parent::runBare();
		$this->addToAssertionCount($numberOfAssertions);
	}

	/**
	 * @test
	 */
	public function inlineTemplateIsEvaluatedCorrectly() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->assign('foo', 'bar');
		$standaloneView->setTemplateSource('This is my cool {foo} template!');

		$expected = 'This is my cool bar template!';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function renderSectionIsEvaluatedCorrectly() {
		$request = new \TYPO3\FLOW3\MVC\Web\Request();
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->assign('foo', 'bar');
		$standaloneView->setTemplateSource('Around stuff... <f:section name="innerSection">test {foo}</f:section> after it');

		$expected = 'test bar';
		$actual = $standaloneView->renderSection('innerSection');
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	public function renderThrowsExceptionIfNeitherTemplateSourceNorTemplatePathAndFileNameAreSpecified() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->render();
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	public function renderThrowsExceptionSpecifiedTemplatePathAndFileNameDoesNotExist() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/NonExistingTemplate.txt');
		$standaloneView->render();
	}

	/**
	 * @test
	 */
	public function templatePathAndFilenameIsLoaded() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->assign('name', 'Karsten');
		$standaloneView->assign('name', 'Robert');
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/TestTemplate.txt');

		$expected = 'This is a test template. Hello Robert.';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function variablesAreEscapedByDefault() {
		$standaloneView = $this->createStandaloneView();
		$standaloneView->assign('name', 'Sebastian <script>alert("dangerous");</script>');
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/TestTemplate.txt');

		$expected = 'This is a test template. Hello Sebastian &lt;script&gt;alert(&quot;dangerous&quot;);&lt;/script&gt;.';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function variablesAreEscapedIfRequestFormatIsHtml() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$request->setFormat('html');
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->assign('name', 'Sebastian <script>alert("dangerous");</script>');
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/TestTemplate.txt');

		$expected = 'This is a test template. Hello Sebastian &lt;script&gt;alert(&quot;dangerous&quot;);&lt;/script&gt;.';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function variablesAreNotEscapedIfRequestFormatIsNotHtml() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$request->setFormat('txt');
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->assign('name', 'Sebastian <script>alert("dangerous");</script>');
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/TestTemplate.txt');

		$expected = 'This is a test template. Hello Sebastian <script>alert("dangerous");</script>.';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function partialWithDefaultLocationIsUsedIfNoPartialPathIsSetExplicitely() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$request->setFormat('txt');
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/TestTemplateWithPartial.txt');

		$expected = 'This is a test template. Hello Robert.';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function explicitPartialPathIsUsed() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$request->setFormat('txt');
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/TestTemplateWithPartial.txt');
		$standaloneView->setPartialRootPath(__DIR__ . '/Fixtures/SpecialPartialsDirectory');

		$expected = 'This is a test template. Hello Karsten.';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function layoutWithDefaultLocationIsUsedIfNoLayoutPathIsSetExplicitely() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$request->setFormat('txt');
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/TestTemplateWithLayout.txt');

		$expected = 'Hey HEY HO';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function explicitLayoutPathIsUsed() {
		$request = new \TYPO3\FLOW3\Mvc\ActionRequest();
		$request->setFormat('txt');
		$standaloneView = $this->createStandaloneView($request);
		$standaloneView->setTemplatePathAndFilename(__DIR__ . '/Fixtures/TestTemplateWithLayout.txt');
		$standaloneView->setLayoutRootPath(__DIR__ . '/Fixtures/SpecialLayouts');

		$expected = 'Hey -- overridden -- HEY HO';
		$actual = $standaloneView->render();
		$this->assertSame($expected, $actual);
	}

	/**
	 * Create a StandaloneView which has a custom prefix (taking $this->standaloneViewNonce
	 * into account).
	 *
	 * @param \TYPO3\FLOW3\Mvc\ActionRequest $request
	 * @return \TYPO3\Fluid\View\StandaloneView
	 */
	protected function createStandaloneView(\TYPO3\FLOW3\Mvc\ActionRequest $request = NULL) {
		$standaloneViewClassName = uniqid('StandaloneView', FALSE);
		eval("class $standaloneViewClassName extends \TYPO3\Fluid\View\StandaloneView {" . '
				protected function createIdentifierForFile($pathAndFilename, $prefix) {
					$prefix = \'' . $this->standaloneViewNonce . '\' . $prefix;
					return parent::createIdentifierForFile($pathAndFilename, $prefix);
				}
		}');

		return new $standaloneViewClassName($request);
	}
}
?>