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

require_once(__DIR__ . '/../ViewHelperBaseTestcase.php');
require_once(__DIR__ . '/../Fixtures/UserWithoutToString.php');
require_once(__DIR__ . '/../Fixtures/UserWithToString.php');

use TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase;
use TYPO3\Fluid\ViewHelpers\Fixtures\UserWithoutToString;
use TYPO3\Fluid\ViewHelpers\Fixtures\UserWithToString;

/**
 * Test for \TYPO3\Fluid\ViewHelpers\Format\StripTagsViewHelper
 */
class StripTagsViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var \TYPO3\Fluid\ViewHelpers\Format\StripTagsViewHelper
     */
    protected $viewHelper;

    public function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\StripTagsViewHelper', array('renderChildren'));
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function viewHelperDeactivatesEscapingInterceptor()
    {
        $this->assertFalse($this->viewHelper->isEscapingInterceptorEnabled());
    }

    /**
     * @test
     */
    public function renderUsesValueAsSourceIfSpecified()
    {
        $this->viewHelper->expects($this->never())->method('renderChildren');
        $actualResult = $this->viewHelper->render('Some string');
        $this->assertEquals('Some string', $actualResult);
    }

    /**
     * @test
     */
    public function renderUsesChildnodesAsSourceIfSpecified()
    {
        $this->viewHelper->expects($this->atLeastOnce())->method('renderChildren')->will($this->returnValue('Some string'));
        $actualResult = $this->viewHelper->render();
        $this->assertEquals('Some string', $actualResult);
    }

    /**
     * Data Provider for the render tests
     *
     * @return array
     */
    public function stringsTestDataProvider()
    {
        return array(
            array('This is a sample text without special characters.', 'This is a sample text without special characters.'),
            array('This is a sample text <b>with <i>some</i> tags</b>.', 'This is a sample text with some tags.'),
            array('This text contains some &quot;&Uuml;mlaut&quot;.', 'This text contains some &quot;&Uuml;mlaut&quot;.')
        );
    }

    /**
     * @test
     * @dataProvider stringsTestDataProvider
     */
    public function renderCorrectlyConvertsIntoPlaintext($source, $expectedResult)
    {
        $actualResult = $this->viewHelper->render($source);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test
     */
    public function renderReturnsUnmodifiedSourceIfItIsANumber()
    {
        $source = 123.45;
        $actualResult = $this->viewHelper->render($source);
        $this->assertSame($source, $actualResult);
    }

    /**
     * @test
     */
    public function renderConvertsObjectsToStrings()
    {
        $user = new UserWithToString('Xaver <b>Cross-Site</b>');
        $expectedResult = 'Xaver Cross-Site';
        $actualResult = $this->viewHelper->render($user);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test
     */
    public function renderDoesNotModifySourceIfItIsAnObjectThatCantBeConvertedToAString()
    {
        $user = new UserWithoutToString('Xaver <b>Cross-Site</b>');
        $actualResult = $this->viewHelper->render($user);
        $this->assertSame($user, $actualResult);
    }
}
