<?php

declare(strict_types=1);

namespace Neos\FluidAdaptor\Tests\Unit\Core\Widget;

/*
 * This file is part of the Neos.FluidAdaptor package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\FluidAdaptor\Core\Widget\Exception\MissingControllerException;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\AbstractNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\RootNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\TextNode;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Testcase for AbstractWidgetViewHelper
 */
final class AbstractWidgetViewHelperTest extends \Neos\Flow\Tests\UnitTestCase
{
    /**
     * @var \Neos\FluidAdaptor\Core\Widget\AbstractWidgetViewHelper
     */
    protected $viewHelper;

    /**
     * @var \Neos\FluidAdaptor\Core\Widget\AjaxWidgetContextHolder
     */
    protected $ajaxWidgetContextHolder;

    /**
     * @var \Neos\FluidAdaptor\Core\Widget\WidgetContext
     */
    protected $widgetContext;

    /**
     */
    protected function setUp(): void
    {
        $this->viewHelper = $this->getAccessibleMock(\Neos\FluidAdaptor\Core\Widget\AbstractWidgetViewHelper::class, ['validateArguments', 'initialize', 'callRenderMethod', 'getWidgetConfiguration']);

        $this->ajaxWidgetContextHolder = $this->createMock(\Neos\FluidAdaptor\Core\Widget\AjaxWidgetContextHolder::class);
        $this->viewHelper->injectAjaxWidgetContextHolder($this->ajaxWidgetContextHolder);

        $this->widgetContext = $this->createMock(\Neos\FluidAdaptor\Core\Widget\WidgetContext::class);
        $this->viewHelper->injectWidgetContext($this->widgetContext);

        $objectManager = $this->createMock(\Neos\Flow\ObjectManagement\ObjectManagerInterface::class);
        $objectManager->method('get')->with(\Neos\FluidAdaptor\Core\Widget\WidgetContext::class)->willReturn(($this->widgetContext));
        $this->viewHelper->injectObjectManager($objectManager);

        $controllerContext = $this->createMock(\Neos\Flow\Mvc\Controller\ControllerContext::class);
        $this->viewHelper->_set('controllerContext', $controllerContext);

        $request = $this->createMock(\Neos\Flow\Mvc\ActionRequest::class);
    }

    /**
     * @test
     */
    public function initializeArgumentsAndRenderCallsTheRightSequenceOfMethods()
    {
        $this->callViewHelper();
    }

    /**
     * @test
     */
    public function initializeArgumentsAndRenderDoesNotStoreTheWidgetContextForStatelessWidgets()
    {
        $this->viewHelper->_set('ajaxWidget', true);
        $this->viewHelper->_set('storeConfigurationInSession', false);
        $this->ajaxWidgetContextHolder->expects($this->never())->method('store');

        $this->callViewHelper();
    }

    /**
     * @test
     */
    public function initializeArgumentsAndRenderStoresTheWidgetContextIfInAjaxMode()
    {
        $this->viewHelper->_set('ajaxWidget', true);
        $this->ajaxWidgetContextHolder->expects($this->once())->method('store')->with($this->widgetContext);

        $this->callViewHelper();
    }

    /**
     * Calls the ViewHelper, and emulates a rendering.
     *
     * @return void
     */
    public function callViewHelper()
    {
        $this->viewHelper->method('getWidgetConfiguration')->willReturn((['Some Widget Configuration']));
        $this->widgetContext->expects($this->once())->method('setNonAjaxWidgetConfiguration')->with(['Some Widget Configuration']);

        $this->widgetContext->expects($this->once())->method('setWidgetIdentifier')->with(strtolower(str_replace('\\', '-', get_class($this->viewHelper))));

        $this->viewHelper->_set('controller', new \stdClass());
        $this->widgetContext->expects($this->once())->method('setControllerObjectName')->with('stdClass');

        $this->viewHelper->expects($this->once())->method('validateArguments');
        $this->viewHelper->expects($this->once())->method('initialize');
        $this->viewHelper->expects($this->once())->method('callRenderMethod')->willReturn(('renderedResult'));
        $output = $this->viewHelper->initializeArgumentsAndRender(['arg1' => 'val1']);
        self::assertEquals('renderedResult', $output);
    }

    /**
     * @test
     */
    public function setChildNodesAddsChildNodesToWidgetContext()
    {
        $this->widgetContext = new \Neos\FluidAdaptor\Core\Widget\WidgetContext();
        $this->viewHelper->injectWidgetContext($this->widgetContext);

        $node1 = $this->createStub(AbstractNode::class);
        $node2 = $this->createStub(TextNode::class);
        $node3 = $this->createStub(AbstractNode::class);

        $rootNode = new RootNode();
        $rootNode->addChildNode($node1);
        $rootNode->addChildNode($node2);
        $rootNode->addChildNode($node3);

        $renderingContext = $this->createStub(RenderingContextInterface::class);
        $this->viewHelper->_set('renderingContext', $renderingContext);

        $this->viewHelper->setChildNodes([$node1, $node2, $node3]);

        self::assertEquals($rootNode, $this->widgetContext->getViewHelperChildNodes());
    }

    /**
     * @test
     */
    public function initiateSubRequestThrowsExceptionIfControllerIsNoWidgetController()
    {
        $this->expectException(MissingControllerException::class);
        $controller = $this->createStub(\Neos\Flow\Mvc\Controller\ControllerInterface::class);
        $this->viewHelper->_set('controller', $controller);

        $this->viewHelper->_call('initiateSubRequest');
    }
}
