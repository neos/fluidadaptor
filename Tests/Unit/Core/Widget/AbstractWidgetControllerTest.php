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
use PHPUnit\Framework\Attributes\Test;
use Neos\Flow\Mvc\ActionRequest;
use Neos\FluidAdaptor\Core\Widget\AbstractWidgetController;
use Neos\Flow\Mvc\Controller\MvcPropertyMappingConfigurationService;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Flow\Tests\UnitTestCase;
use Neos\FluidAdaptor\Core\Widget\Exception\WidgetContextNotFoundException;
use Neos\FluidAdaptor\Core\Widget\WidgetContext;

/**
 * Test case for AbstractWidgetController
 */
final class AbstractWidgetControllerTest extends UnitTestCase
{
    #[Test]
    public function processRequestShouldThrowExceptionIfWidgetContextNotFound()
    {
        $this->expectException(WidgetContextNotFoundException::class);
        /** @var \Neos\Flow\Mvc\ActionRequest $mockActionRequest */
        $mockActionRequest = $this->createMock(ActionRequest::class);
        $mockActionRequest->expects($this->atLeastOnce())->method('getInternalArgument')->with('__widgetContext')->willReturn((null));
        $response = new ActionResponse();

        /** @var \Neos\FluidAdaptor\Core\Widget\AbstractWidgetController $abstractWidgetController */
        $abstractWidgetController = $this->getMockForAbstractClass(AbstractWidgetController::class);
        $abstractWidgetController->processRequest($mockActionRequest, $response);
    }

    #[Test]
    public function processRequestShouldSetWidgetConfiguration()
    {
        /** @var \Neos\Flow\Mvc\ActionRequest $mockActionRequest */
        $mockActionRequest = $this->createMock(ActionRequest::class);
        $mockResponse = new ActionResponse();

        $httpRequest = new ServerRequest('GET', new Uri('http://localhost'));
        $mockActionRequest->method('getHttpRequest')->willReturn(($httpRequest));

        $expectedWidgetConfiguration = ['foo' => uniqid()];

        $widgetContext = new WidgetContext();
        $widgetContext->setAjaxWidgetConfiguration($expectedWidgetConfiguration);

        $mockActionRequest->expects($this->atLeastOnce())->method('getInternalArgument')->with('__widgetContext')->willReturn(($widgetContext));

        $abstractWidgetController = $this->getAccessibleMock(AbstractWidgetController::class, ['resolveActionMethodName', 'initializeActionMethodArguments', 'initializeActionMethodValidators', 'mapRequestArgumentsToControllerArguments', 'detectFormat', 'resolveView', 'callActionMethod']);
        $abstractWidgetController->method('resolveActionMethodName')->willReturn('indexAction');
        $abstractWidgetController->_set('mvcPropertyMappingConfigurationService', $this->createMock(MvcPropertyMappingConfigurationService::class));

        $abstractWidgetController->processRequest($mockActionRequest, $mockResponse);

        $actualWidgetConfiguration = $abstractWidgetController->_get('widgetConfiguration');
        self::assertEquals($expectedWidgetConfiguration, $actualWidgetConfiguration);
    }
}
