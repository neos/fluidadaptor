<?php
namespace Neos\FluidAdaptor\Tests\Unit\ViewHelpers\Form;

/*
 * This file is part of the Neos.FluidAdaptor package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\FluidAdaptor\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase;

require_once(__DIR__ . '/../ViewHelperBaseTestcase.php');

/**
 * Test for the "Radio" Form view helper
 */
class RadioViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var \Neos\FluidAdaptor\ViewHelpers\Form\RadioViewHelper
     */
    protected $viewHelper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder
     */
    protected $mockTagBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewHelper = $this->getAccessibleMock(\Neos\FluidAdaptor\ViewHelpers\Form\RadioViewHelper::class, ['setErrorClassAttribute', 'getName', 'getValueAttribute', 'isObjectAccessorMode', 'getPropertyValue', 'registerFieldNameForFormTokenGeneration']);
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->mockTagBuilder = $this->getMockBuilder(\TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder::class)->setMethods(['setTagName', 'addAttribute'])->getMock();
    }

    /**
     * @test
     */
    public function renderCorrectlySetsTagNameAndDefaultAttributes()
    {
        $this->mockTagBuilder->expects(self::atLeastOnce())->method('setTagName')->with('input');
        $matcher = self::exactly(3);
        $this->mockTagBuilder->expects($matcher)->method('addAttribute')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('type', $parameters[0]);
                $this->assertSame('radio', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('name', $parameters[0]);
                $this->assertSame('foo', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 3) {
                $this->assertSame('value', $parameters[0]);
                $this->assertSame('bar', $parameters[1]);
            }
        });

        $this->viewHelper->expects(self::once())->method('registerFieldNameForFormTokenGeneration')->with('foo');
        $this->viewHelper->expects(self::any())->method('getName')->will(self::returnValue('foo'));
        $this->viewHelper->expects(self::any())->method('getValueAttribute')->will(self::returnValue('bar'));
        $this->viewHelper->injectTagBuilder($this->mockTagBuilder);

        $this->viewHelper = $this->prepareArguments($this->viewHelper, []);
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderSetsCheckedAttributeIfSpecified()
    {
        $matcher = self::exactly(4);
        $this->mockTagBuilder->expects($matcher)->method('addAttribute')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('type', $parameters[0]);
                $this->assertSame('radio', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('name', $parameters[0]);
                $this->assertSame('foo', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 3) {
                $this->assertSame('value', $parameters[0]);
                $this->assertSame('bar', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 4) {
                $this->assertSame('checked', $parameters[0]);
                $this->assertSame('', $parameters[1]);
            }
        });

        $this->viewHelper->expects(self::once())->method('registerFieldNameForFormTokenGeneration')->with('foo');
        $this->viewHelper->expects(self::any())->method('getName')->will(self::returnValue('foo'));
        $this->viewHelper->expects(self::any())->method('getValueAttribute')->will(self::returnValue('bar'));
        $this->viewHelper->injectTagBuilder($this->mockTagBuilder);

        $this->viewHelper = $this->prepareArguments($this->viewHelper, ['checked' => true]);
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderIgnoresBoundPropertyIfCheckedIsSet()
    {
        $matcher = self::exactly(7);
        $this->mockTagBuilder->expects($matcher)->method('addAttribute')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('type', $parameters[0]);
                $this->assertSame('radio', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('name', $parameters[0]);
                $this->assertSame('foo', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 3) {
                $this->assertSame('value', $parameters[0]);
                $this->assertSame('bar', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 4) {
                $this->assertSame('checked', $parameters[0]);
                $this->assertSame('', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 5) {
                $this->assertSame('type', $parameters[0]);
                $this->assertSame('radio', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 6) {
                $this->assertSame('name', $parameters[0]);
                $this->assertSame('foo', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 7) {
                $this->assertSame('value', $parameters[0]);
                $this->assertSame('bar', $parameters[1]);
            }
        });

        $this->viewHelper->expects(self::any())->method('getName')->will(self::returnValue('foo'));
        $this->viewHelper->expects(self::any())->method('getValueAttribute')->will(self::returnValue('bar'));
        $this->viewHelper->expects(self::any())->method('isObjectAccessorMode')->will(self::returnValue(true));
        $this->viewHelper->expects(self::any())->method('getPropertyValue')->will(self::returnValue('propertyValue'));
        $this->viewHelper->injectTagBuilder($this->mockTagBuilder);

        $this->viewHelper = $this->prepareArguments($this->viewHelper, ['checked' => true]);
        $this->viewHelper->render();

        $this->viewHelper = $this->prepareArguments($this->viewHelper, ['checked' => false]);
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderCorrectlySetsCheckedAttributeIfCheckboxIsBoundToAPropertyOfTypeBoolean()
    {
        $matcher = self::exactly(4);
        $this->mockTagBuilder->expects($matcher)->method('addAttribute')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('type', $parameters[0]);
                $this->assertSame('radio', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('name', $parameters[0]);
                $this->assertSame('foo', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 3) {
                $this->assertSame('value', $parameters[0]);
                $this->assertSame('bar', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 4) {
                $this->assertSame('checked', $parameters[0]);
                $this->assertSame('', $parameters[1]);
            }
        });

        $this->viewHelper->expects(self::once())->method('registerFieldNameForFormTokenGeneration')->with('foo');
        $this->viewHelper->expects(self::any())->method('getName')->will(self::returnValue('foo'));
        $this->viewHelper->expects(self::any())->method('getValueAttribute')->will(self::returnValue('bar'));
        $this->viewHelper->expects(self::any())->method('isObjectAccessorMode')->will(self::returnValue(true));
        $this->viewHelper->expects(self::any())->method('getPropertyValue')->will(self::returnValue(true));
        $this->viewHelper->injectTagBuilder($this->mockTagBuilder);

        $this->viewHelper = $this->prepareArguments($this->viewHelper);
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderDoesNotAppendSquareBracketsToNameAttributeIfBoundToAPropertyOfTypeArray()
    {
        $matcher = self::exactly(3);
        $this->mockTagBuilder->expects($matcher)->method('addAttribute')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('type', $parameters[0]);
                $this->assertSame('radio', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('name', $parameters[0]);
                $this->assertSame('foo', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 3) {
                $this->assertSame('value', $parameters[0]);
                $this->assertSame('bar', $parameters[1]);
            }
        });

        $this->viewHelper->expects(self::once())->method('registerFieldNameForFormTokenGeneration')->with('foo');
        $this->viewHelper->expects(self::any())->method('getName')->will(self::returnValue('foo'));
        $this->viewHelper->expects(self::any())->method('getValueAttribute')->will(self::returnValue('bar'));
        $this->viewHelper->expects(self::any())->method('isObjectAccessorMode')->will(self::returnValue(true));
        $this->viewHelper->expects(self::any())->method('getPropertyValue')->will(self::returnValue([]));
        $this->viewHelper->injectTagBuilder($this->mockTagBuilder);


        $this->viewHelper = $this->prepareArguments($this->viewHelper);
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderCorrectlySetsCheckedAttributeIfCheckboxIsBoundToAPropertyOfTypeString()
    {
        $matcher = self::exactly(4);
        $this->mockTagBuilder->expects($matcher)->method('addAttribute')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('type', $parameters[0]);
                $this->assertSame('radio', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('name', $parameters[0]);
                $this->assertSame('foo', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 3) {
                $this->assertSame('value', $parameters[0]);
                $this->assertSame('bar', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 4) {
                $this->assertSame('checked', $parameters[0]);
                $this->assertSame('', $parameters[1]);
            }
        });

        $this->viewHelper->expects(self::once())->method('registerFieldNameForFormTokenGeneration')->with('foo');
        $this->viewHelper->expects(self::any())->method('getName')->will(self::returnValue('foo'));
        $this->viewHelper->expects(self::any())->method('getValueAttribute')->will(self::returnValue('bar'));
        $this->viewHelper->expects(self::any())->method('isObjectAccessorMode')->will(self::returnValue(true));
        $this->viewHelper->expects(self::any())->method('getPropertyValue')->will(self::returnValue('bar'));
        $this->viewHelper->injectTagBuilder($this->mockTagBuilder);

        $this->viewHelper = $this->prepareArguments($this->viewHelper);
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderCallsSetErrorClassAttribute()
    {
        $this->viewHelper->expects(self::once())->method('setErrorClassAttribute');
        $this->viewHelper = $this->prepareArguments($this->viewHelper);
        $this->viewHelper->render();
    }
}
