<?php
namespace TYPO3\Fluid\Tests\Unit\View;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */

/**
 * Testcase for the TemplateView
 */
class AbstractTemplateViewTest extends \TYPO3\Flow\Tests\UnitTestCase
{
    /**
     * @var \TYPO3\Fluid\View\AbstractTemplateView
     */
    protected $view;

    /**
     * @var \TYPO3\Fluid\Core\Rendering\RenderingContext
     */
    protected $renderingContext;

    /**
     * @var \TYPO3\Fluid\Core\ViewHelper\ViewHelperVariableContainer
     */
    protected $viewHelperVariableContainer;

    /**
     * @var \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer
     */
    protected $templateVariableContainer;

    /**
     * Sets up this test case
     *
     * @return void
     */
    public function setUp()
    {
        $this->templateVariableContainer = $this->getMock('TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer', array('exists', 'remove', 'add'));
        $this->viewHelperVariableContainer = $this->getMock('TYPO3\Fluid\Core\ViewHelper\ViewHelperVariableContainer', array('setView'));
        $this->renderingContext = $this->getMock('TYPO3\Fluid\Core\Rendering\RenderingContext', array('getViewHelperVariableContainer', 'getTemplateVariableContainer'));
        $this->renderingContext->expects($this->any())->method('getViewHelperVariableContainer')->will($this->returnValue($this->viewHelperVariableContainer));
        $this->renderingContext->expects($this->any())->method('getTemplateVariableContainer')->will($this->returnValue($this->templateVariableContainer));
        $this->view = $this->getMock('TYPO3\Fluid\View\AbstractTemplateView', array('getTemplateSource', 'getLayoutSource', 'getPartialSource', 'canRender', 'getTemplateIdentifier', 'getLayoutIdentifier', 'getPartialIdentifier'));
        $this->view->setRenderingContext($this->renderingContext);
    }

    /**
     * @test
     */
    public function viewIsPlacedInViewHelperVariableContainer()
    {
        $this->viewHelperVariableContainer->expects($this->once())->method('setView')->with($this->view);
        $this->view->setRenderingContext($this->renderingContext);
    }

    /**
     * @test
     */
    public function assignAddsValueToTemplateVariableContainer()
    {
        $this->templateVariableContainer->expects($this->at(0))->method('exists')->with('foo')->will($this->returnValue(false));
        $this->templateVariableContainer->expects($this->at(1))->method('add')->with('foo', 'FooValue');
        $this->templateVariableContainer->expects($this->at(2))->method('exists')->with('bar')->will($this->returnValue(false));
        $this->templateVariableContainer->expects($this->at(3))->method('add')->with('bar', 'BarValue');

        $this->view
            ->assign('foo', 'FooValue')
            ->assign('bar', 'BarValue');
    }

    /**
     * @test
     */
    public function assignCanOverridePreviouslyAssignedValues()
    {
        $this->templateVariableContainer->expects($this->at(0))->method('exists')->with('foo')->will($this->returnValue(false));
        $this->templateVariableContainer->expects($this->at(1))->method('add')->with('foo', 'FooValue');
        $this->templateVariableContainer->expects($this->at(2))->method('exists')->with('foo')->will($this->returnValue(true));
        $this->templateVariableContainer->expects($this->at(3))->method('remove')->with('foo');
        $this->templateVariableContainer->expects($this->at(4))->method('add')->with('foo', 'FooValueOverridden');

        $this->view->assign('foo', 'FooValue');
        $this->view->assign('foo', 'FooValueOverridden');
    }

    /**
     * @test
     */
    public function assignMultipleAddsValuesToTemplateVariableContainer()
    {
        $this->templateVariableContainer->expects($this->at(0))->method('exists')->with('foo')->will($this->returnValue(false));
        $this->templateVariableContainer->expects($this->at(1))->method('add')->with('foo', 'FooValue');
        $this->templateVariableContainer->expects($this->at(2))->method('exists')->with('bar')->will($this->returnValue(false));
        $this->templateVariableContainer->expects($this->at(3))->method('add')->with('bar', 'BarValue');
        $this->templateVariableContainer->expects($this->at(4))->method('exists')->with('baz')->will($this->returnValue(false));
        $this->templateVariableContainer->expects($this->at(5))->method('add')->with('baz', 'BazValue');

        $this->view
            ->assignMultiple(array('foo' => 'FooValue', 'bar' => 'BarValue'))
            ->assignMultiple(array('baz' => 'BazValue'));
    }

    /**
     * @test
     */
    public function assignMultipleCanOverridePreviouslyAssignedValues()
    {
        $this->templateVariableContainer->expects($this->at(0))->method('exists')->with('foo')->will($this->returnValue(false));
        $this->templateVariableContainer->expects($this->at(1))->method('add')->with('foo', 'FooValue');
        $this->templateVariableContainer->expects($this->at(2))->method('exists')->with('foo')->will($this->returnValue(true));
        $this->templateVariableContainer->expects($this->at(3))->method('remove')->with('foo');
        $this->templateVariableContainer->expects($this->at(4))->method('add')->with('foo', 'FooValueOverridden');
        $this->templateVariableContainer->expects($this->at(5))->method('exists')->with('bar')->will($this->returnValue(false));
        $this->templateVariableContainer->expects($this->at(6))->method('add')->with('bar', 'BarValue');

        $this->view->assign('foo', 'FooValue');
        $this->view->assignMultiple(array('foo' => 'FooValueOverridden', 'bar' => 'BarValue'));
    }
}
