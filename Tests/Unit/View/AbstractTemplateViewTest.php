<?php
namespace Neos\FluidAdaptor\Tests\Unit\View;

/*
 * This file is part of the Neos.FluidAdaptor package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */
use Neos\FluidAdaptor\Core\Rendering\RenderingContext;
use Neos\FluidAdaptor\Core\ViewHelper\TemplateVariableContainer;
use Neos\FluidAdaptor\View\AbstractTemplateView;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer;

/**
 * Testcase for the TemplateView
 */
class AbstractTemplateViewTest extends \Neos\Flow\Tests\UnitTestCase
{
    /**
     * @var AbstractTemplateView
     */
    protected $view;

    /**
     * @var RenderingContext
     */
    protected $renderingContext;

    /**
     * @var ViewHelperVariableContainer
     */
    protected $viewHelperVariableContainer;

    /**
     * @var TemplateVariableContainer
     */
    protected $templateVariableContainer;

    /**
     * Sets up this test case
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->templateVariableContainer = $this->getMockBuilder(TemplateVariableContainer::class)->setMethods(['exists', 'remove', 'add'])->getMock();
        $this->viewHelperVariableContainer = $this->getMockBuilder(ViewHelperVariableContainer::class)->setMethods(['setView'])->getMock();
        $this->renderingContext = $this->getMockBuilder(RenderingContext::class)->setMethods(['getViewHelperVariableContainer', 'getVariableProvider'])->disableOriginalConstructor()->getMock();
        $this->renderingContext->expects(self::any())->method('getViewHelperVariableContainer')->will(self::returnValue($this->viewHelperVariableContainer));
        $this->renderingContext->expects(self::any())->method('getVariableProvider')->will(self::returnValue($this->templateVariableContainer));
        $this->view = $this->getMockBuilder(AbstractTemplateView::class)->setMethods(['getTemplateSource', 'getLayoutSource', 'getPartialSource', 'canRender', 'getTemplateIdentifier', 'getLayoutIdentifier', 'getPartialIdentifier'])->getMock();
        $this->view->setRenderingContext($this->renderingContext);
    }

    /**
     * @test
     */
    public function viewIsPlacedInViewHelperVariableContainer()
    {
        $this->viewHelperVariableContainer->expects(self::once())->method('setView')->with($this->view);
        $this->view->setRenderingContext($this->renderingContext);
    }

    /**
     * @test
     */
    public function assignAddsValueToTemplateVariableContainer()
    {
        $matcher = self::exactly(2);
        $this->templateVariableContainer->expects($matcher)->method('add')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('foo', $parameters[0]);
                $this->assertSame('FooValue', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('bar', $parameters[0]);
                $this->assertSame('BarValue', $parameters[1]);
            }
        });

        $this->view
            ->assign('foo', 'FooValue')
            ->assign('bar', 'BarValue');
    }

    /**
     * @test
     */
    public function assignCanOverridePreviouslyAssignedValues()
    {
        $matcher = self::exactly(2);
        $this->templateVariableContainer->expects($matcher)->method('add')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('foo', $parameters[0]);
                $this->assertSame('FooValue', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('foo', $parameters[0]);
                $this->assertSame('FooValueOverridden', $parameters[1]);
            }
        });

        $this->view->assign('foo', 'FooValue');
        $this->view->assign('foo', 'FooValueOverridden');
    }

    /**
     * @test
     */
    public function assignMultipleAddsValuesToTemplateVariableContainer()
    {
        $matcher = self::exactly(3);
        $this->templateVariableContainer->expects($matcher)->method('add')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('foo', $parameters[0]);
                $this->assertSame('FooValue', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('bar', $parameters[0]);
                $this->assertSame('BarValue', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 3) {
                $this->assertSame('baz', $parameters[0]);
                $this->assertSame('BazValue', $parameters[1]);
            }
        });

        $this->view
            ->assignMultiple(['foo' => 'FooValue', 'bar' => 'BarValue'])
            ->assignMultiple(['baz' => 'BazValue']);
    }

    /**
     * @test
     */
    public function assignMultipleCanOverridePreviouslyAssignedValues()
    {
        $matcher = self::exactly(3);
        $this->templateVariableContainer->expects($matcher)->method('add')->willReturnCallback(function (...$parameters) use ($matcher) {
            if ($matcher->getInvocationCount() === 1) {
                $this->assertSame('foo', $parameters[0]);
                $this->assertSame('FooValue', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 2) {
                $this->assertSame('foo', $parameters[0]);
                $this->assertSame('FooValueOverridden', $parameters[1]);
            }
            if ($matcher->getInvocationCount() === 3) {
                $this->assertSame('bar', $parameters[0]);
                $this->assertSame('BarValue', $parameters[1]);
            }
        });

        $this->view->assign('foo', 'FooValue');
        $this->view->assignMultiple(['foo' => 'FooValueOverridden', 'bar' => 'BarValue']);
    }
}
