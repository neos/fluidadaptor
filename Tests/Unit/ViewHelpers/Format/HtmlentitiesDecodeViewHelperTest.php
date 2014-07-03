<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/../Fixtures/UserWithoutToString.php');
require_once(__DIR__ . '/../Fixtures/UserWithToString.php');

use TYPO3\Fluid\ViewHelpers\Fixtures\UserWithoutToString;
use TYPO3\Fluid\ViewHelpers\Fixtures\UserWithToString;

/**
 * Test for \TYPO3\Fluid\ViewHelpers\Format\HtmlentitiesDecodeViewHelper
 */
class HtmlentitiesDecodeViewHelperTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\Fluid\ViewHelpers\Format\HtmlentitiesDecodeViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		$this->viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\HtmlentitiesDecodeViewHelper', array('renderChildren'));
	}

	/**
	 * @test
	 */
	public function viewHelperDeactivatesEscapingInterceptor() {
		$this->assertFalse($this->viewHelper->isEscapingInterceptorEnabled());
	}

	/**
	 * @test
	 */
	public function renderUsesValueAsSourceIfSpecified() {
		$this->viewHelper->expects($this->never())->method('renderChildren');
		$actualResult = $this->viewHelper->render('Some string');
		$this->assertEquals('Some string', $actualResult);
	}

	/**
	 * @test
	 */
	public function renderUsesChildnodesAsSourceIfSpecified() {
		$this->viewHelper->expects($this->atLeastOnce())->method('renderChildren')->will($this->returnValue('Some string'));
		$actualResult = $this->viewHelper->render();
		$this->assertEquals('Some string', $actualResult);
	}

	/**
	 * @test
	 */
	public function renderDoesNotModifyValueIfItDoesNotContainSpecialCharacters() {
		$source = 'This is a sample text without special characters. <> &©"\'';
		$actualResult = $this->viewHelper->render($source);
		$this->assertSame($source, $actualResult);
	}

	/**
	 * @test
	 */
	public function renderDecodesSimpleString() {
		$source = 'Some special characters: &amp; &quot; \' &lt; &gt; *';
		$expectedResult = 'Some special characters: & " \' < > *';
		$actualResult = $this->viewHelper->render($source);
		$this->assertEquals($expectedResult, $actualResult);
	}

	/**
	 * @test
	 */
	public function renderRespectsKeepQuoteArgument() {
		$source = 'Some special characters: &amp; &quot; \' &lt; &gt; *';
		$expectedResult = 'Some special characters: & &quot; \' < > *';
		$actualResult = $this->viewHelper->render($source, TRUE);
		$this->assertEquals($expectedResult, $actualResult);
	}

	/**
	 * @test
	 */
	public function renderRespectsEncodingArgument() {
		$source = utf8_decode('Some special characters: &amp; &quot; \' &lt; &gt; *');
		$expectedResult = 'Some special characters: & " \' < > *';
		$actualResult = $this->viewHelper->render($source, FALSE, 'ISO-8859-1');
		$this->assertEquals($expectedResult, $actualResult);
	}

	/**
	 * @test
	 */
	public function renderReturnsUnmodifiedSourceIfItIsANumber() {
		$source = 123.45;
		$actualResult = $this->viewHelper->render($source);
		$this->assertSame($source, $actualResult);
	}

	/**
	 * @test
	 */
	public function renderDecodesObjectsToStrings() {
		$user = new UserWithToString('Xaver &lt;b&gt;Cross-Site&lt;/b&gt;');
		$expectedResult = 'Xaver <b>Cross-Site</b>';
		$actualResult = $this->viewHelper->render($user);
		$this->assertSame($expectedResult, $actualResult);
	}

	/**
	 * @test
	 */
	public function renderDoesNotModifySourceIfItIsAnObjectThatCantBeConvertedToAString() {
		$user = new UserWithoutToString('Xaver <b>Cross-Site</b>');
		$actualResult = $this->viewHelper->render($user);
		$this->assertSame($user, $actualResult);
	}
}
