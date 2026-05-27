<?php

declare(strict_types=1);

namespace Neos\FluidAdaptor\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Neos.FluidAdaptor package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use GuzzleHttp\Psr7\Uri;
use Neos\FluidAdaptor\Core\ViewHelper\TemplateVariableContainer;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractTagBasedViewHelper;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\Http\Factories\ServerRequestFactory;
use Neos\Http\Factories\UriFactory;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

/**
 * Base test class for testing view helpers
 */
abstract class ViewHelperBaseTestcase extends \Neos\Flow\Tests\UnitTestCase
{
    /**
     * @var \TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $viewHelperVariableContainer;

    /**
     * Mock contents of the $viewHelperVariableContainer in the format:
     * array(
     *  'Some\ViewHelper\Class' => array('key1' => 'value1', 'key2' => 'value2')
     * )
     *
     * @var array
     */
    protected $viewHelperVariableContainerData = [];

    /**
     * @var \Neos\FluidAdaptor\Core\ViewHelper\TemplateVariableContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $templateVariableContainer;

    /**
     * @var \Neos\Flow\Mvc\Routing\UriBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $uriBuilder;

    /**
     * @var \Neos\Flow\Mvc\Controller\ControllerContext|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $controllerContext;

    /**
     * @var TagBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $tagBuilder;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var \Neos\Flow\Mvc\ActionRequest|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $request;

    /**
     * @var \Neos\FluidAdaptor\Core\Rendering\RenderingContext|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $renderingContext;

    /**
     * @var ViewHelperInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $viewHelper;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->viewHelperVariableContainer = $this->createMock(\TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer::class);
        $this->viewHelperVariableContainer->method('exists')->willReturnCallback([$this, 'viewHelperVariableContainerExistsCallback']);
        $this->viewHelperVariableContainer->method('get')->willReturnCallback([$this, 'viewHelperVariableContainerGetCallback']);
        $this->viewHelperVariableContainer->method('addOrUpdate')->willReturnCallback([$this, 'viewHelperVariableContainerAddOrUpdateCallback']);
        $this->templateVariableContainer = $this->createMock(TemplateVariableContainer::class);
        $this->uriBuilder = $this->createMock(\Neos\Flow\Mvc\Routing\UriBuilder::class);
        $this->uriBuilder->method('reset')->willReturn(($this->uriBuilder));
        $this->uriBuilder->method('setArguments')->willReturn(($this->uriBuilder));
        $this->uriBuilder->method('setSection')->willReturn(($this->uriBuilder));
        $this->uriBuilder->method('setFormat')->willReturn(($this->uriBuilder));
        $this->uriBuilder->method('setCreateAbsoluteUri')->willReturn(($this->uriBuilder));
        $this->uriBuilder->method('setAddQueryString')->willReturn(($this->uriBuilder));
        $this->uriBuilder->method('setArgumentsToBeExcludedFromQueryString')->willReturn(($this->uriBuilder));

        $httpRequestFactory = new ServerRequestFactory(new UriFactory());
        $httpRequest = $httpRequestFactory->createServerRequest('GET', new Uri('http://localhost/foo'));

        $this->request = $this->createMock(\Neos\Flow\Mvc\ActionRequest::class);
        $this->request->method('isMainRequest')->willReturn((true));
        $this->controllerContext = $this->createMock(\Neos\Flow\Mvc\Controller\ControllerContext::class);
        $this->controllerContext->method('getUriBuilder')->willReturn(($this->uriBuilder));
        $this->controllerContext->method('getRequest')->willReturn(($this->request));
        $this->tagBuilder = $this->createMock(TagBuilder::class);
        $this->arguments = [];
        $this->renderingContext = new \Neos\FluidAdaptor\Core\Rendering\RenderingContext([]);
        $this->renderingContext->setVariableProvider($this->templateVariableContainer);
        $this->renderingContext->setViewHelperVariableContainer($this->viewHelperVariableContainer);
        $this->renderingContext->setControllerContext($this->controllerContext);
    }

    /**
     * @param string $viewHelperName
     * @param string $key
     * @return boolean
     */
    public function viewHelperVariableContainerExistsCallback($viewHelperName, $key)
    {
        return isset($this->viewHelperVariableContainerData[$viewHelperName][$key]);
    }

    /**
     * @param string $viewHelperName
     * @param string $key
     * @return boolean
     */
    public function viewHelperVariableContainerGetCallback($viewHelperName, $key)
    {
        return $this->viewHelperVariableContainerData[$viewHelperName][$key];
    }

    /**
     * @param string $viewHelperName
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function viewHelperVariableContainerAddOrUpdateCallback($viewHelperName, $key, $value)
    {
        $this->viewHelperVariableContainerData[$viewHelperName][$key] = $value;
    }

    /**
     * @param AbstractViewHelper $viewHelper
     */
    protected function injectDependenciesIntoViewHelper(AbstractViewHelper $viewHelper)
    {
        $viewHelper->setRenderingContext($this->renderingContext);
        $viewHelper->setArguments($this->arguments);
        if ($viewHelper instanceof AbstractTagBasedViewHelper) {
            $viewHelper->injectTagBuilder($this->tagBuilder);
        }
    }

    /**
     * @param \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper $viewHelper
     * @param array $testArguments
     * @return \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
     */
    protected function prepareArguments(\TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper $viewHelper, array $testArguments = [])
    {
        $evaluatedArguments = [];
        $argumentDefinitions = $viewHelper->prepareArguments();
        foreach ($argumentDefinitions as $argumentName => $argumentDefinition) {
            if (isset($testArguments[$argumentName])) {
                $argumentValue = $testArguments[$argumentName];
                $evaluatedArguments[$argumentName] = $argumentValue;
            } else {
                $evaluatedArguments[$argumentName] = $argumentDefinition->getDefaultValue();
            }
        }

        $viewHelper->setArguments($evaluatedArguments);
        $viewHelper->validateArguments();
        $viewHelper->initialize();
        return $viewHelper;
    }
}
