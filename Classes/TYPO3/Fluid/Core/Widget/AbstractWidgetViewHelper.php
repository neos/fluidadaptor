<?php
namespace TYPO3\Fluid\Core\Widget;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


use TYPO3\Flow\Object\DependencyInjection\DependencyProxy;

/**
 * @api
 */
abstract class AbstractWidgetViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper implements \TYPO3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface {

	/**
	 * The Controller associated to this widget.
	 * This needs to be filled by the individual subclass using
	 * property injection.
	 *
	 * @var \TYPO3\Fluid\Core\Widget\AbstractWidgetController
	 * @api
	 */
	protected $controller;

	/**
	 * If set to TRUE, it is an AJAX widget.
	 *
	 * @var boolean
	 * @api
	 */
	protected $ajaxWidget = FALSE;

	/**
	 * If set to FALSE, this widget won't create a session (only relevant for AJAX widgets).
	 *
	 * You then need to manually add the serialized configuration data to your links, by
	 * setting "includeWidgetContext" to TRUE in the widget link and URI ViewHelpers.
	 *
	 * @var boolean
	 * @api
	 */
	protected $storeConfigurationInSession = TRUE;

	/**
	 * @var \TYPO3\Fluid\Core\Widget\AjaxWidgetContextHolder
	 */
	private $ajaxWidgetContextHolder;

	/**
	 * @var \TYPO3\Fluid\Core\Widget\WidgetContext
	 */
	private $widgetContext;

	/**
	 * @param \TYPO3\Fluid\Core\Widget\AjaxWidgetContextHolder $ajaxWidgetContextHolder
	 * @return void
	 */
	public function injectAjaxWidgetContextHolder(\TYPO3\Fluid\Core\Widget\AjaxWidgetContextHolder $ajaxWidgetContextHolder) {
		$this->ajaxWidgetContextHolder = $ajaxWidgetContextHolder;
	}

	/**
	 * @param \TYPO3\Fluid\Core\Widget\WidgetContext $widgetContext
	 * @return void
	 */
	public function injectWidgetContext(\TYPO3\Fluid\Core\Widget\WidgetContext $widgetContext) {
		$this->widgetContext = $widgetContext;
	}

	/**
	 * Registers the widgetId viewhelper
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('widgetId', 'string', 'Unique identifier of the widget instance');
	}

	/**
	 * Initialize the arguments of the ViewHelper, and call the render() method of the ViewHelper.
	 *
	 * @return string the rendered ViewHelper.
	 */
	public function initializeArgumentsAndRender() {
		$this->validateArguments();
		$this->initialize();
		$this->initializeWidgetContext();

		return $this->callRenderMethod();
	}

	/**
	 * Initialize the Widget Context, before the Render method is called.
	 *
	 * @return void
	 */
	private function initializeWidgetContext() {
		if ($this->ajaxWidget === TRUE) {
			if ($this->storeConfigurationInSession === TRUE) {
				$this->ajaxWidgetContextHolder->store($this->widgetContext);
			}
			$this->widgetContext->setAjaxWidgetConfiguration($this->getAjaxWidgetConfiguration());
		}

		$this->widgetContext->setNonAjaxWidgetConfiguration($this->getNonAjaxWidgetConfiguration());
		$this->initializeWidgetIdentifier();

		$controllerObjectName = ($this->controller instanceof DependencyProxy) ? $this->controller->_getClassName() : get_class($this->controller);
		$this->widgetContext->setControllerObjectName($controllerObjectName);
	}

	/**
	 * Stores the syntax tree child nodes in the Widget Context, so they can be
	 * rendered with <f:widget.renderChildren> lateron.
	 *
	 * @param array $childNodes The SyntaxTree Child nodes of this ViewHelper.
	 * @return void
	 */
	public function setChildNodes(array $childNodes) {
		$rootNode = $this->objectManager->get('TYPO3\Fluid\Core\Parser\SyntaxTree\RootNode');
		foreach ($childNodes as $childNode) {
			$rootNode->addChildNode($childNode);
		}
		$this->widgetContext->setViewHelperChildNodes($rootNode, $this->renderingContext);
	}

	/**
	 * Generate the configuration for this widget. Override to adjust.
	 *
	 * @return array
	 * @api
	 */
	protected function getWidgetConfiguration() {
		return $this->arguments;
	}

	/**
	 * Generate the configuration for this widget in AJAX context.
	 *
	 * By default, returns getWidgetConfiguration(). Should become API later.
	 *
	 * @return array
	 */
	protected function getAjaxWidgetConfiguration() {
		return $this->getWidgetConfiguration();
	}

	/**
	 * Generate the configuration for this widget in non-AJAX context.
	 *
	 * By default, returns getWidgetConfiguration(). Should become API later.
	 *
	 * @return array
	 */
	protected function getNonAjaxWidgetConfiguration() {
		return $this->getWidgetConfiguration();
	}

	/**
	 * Initiate a sub request to $this->controller. Make sure to fill $this->controller
	 * via Dependency Injection.
	 *
	 * @return \TYPO3\Flow\Http\Response the response of this request.
	 * @throws Exception\MissingControllerException
	 * @api
	 */
	protected function initiateSubRequest() {
		if ($this->controller instanceof DependencyProxy) {
			$this->controller->_activateDependency();
		}
		if (!($this->controller instanceof \TYPO3\Fluid\Core\Widget\AbstractWidgetController)) {
			throw new \TYPO3\Fluid\Core\Widget\Exception\MissingControllerException('initiateSubRequest() can not be called if there is no controller inside $this->controller. Make sure to add the @TYPO3\Flow\Annotations\Inject annotation in your widget class.', 1284401632);
		}

		$subRequest = $this->objectManager->get('TYPO3\Flow\Mvc\ActionRequest', $this->controllerContext->getRequest());
		$this->passArgumentsToSubRequest($subRequest);
		$subRequest->setArgument('__widgetContext', $this->widgetContext);
		$subRequest->setControllerObjectName($this->widgetContext->getControllerObjectName());
		$subRequest->setArgumentNamespace('--' . $this->widgetContext->getWidgetIdentifier());

		$subResponse = $this->objectManager->get('TYPO3\Flow\Http\Response');
		$this->controller->processRequest($subRequest, $subResponse);
		return $subResponse;
	}

	/**
	 * Pass the arguments of the widget to the subrequest.
	 *
	 * @param \TYPO3\Flow\Mvc\ActionRequest $subRequest
	 * @return void
	 */
	private function passArgumentsToSubRequest(\TYPO3\Flow\Mvc\ActionRequest $subRequest) {
		$arguments = $this->controllerContext->getRequest()->getPluginArguments();
		$widgetIdentifier = $this->widgetContext->getWidgetIdentifier();

		$controllerActionName = 'index';
		if (isset($arguments[$widgetIdentifier])) {
			if (isset($arguments[$widgetIdentifier]['@action'])) {
				$controllerActionName = $arguments[$widgetIdentifier]['@action'];
				unset($arguments[$widgetIdentifier]['@action']);
			}
			$subRequest->setArguments($arguments[$widgetIdentifier]);
		}
		$subRequest->setControllerActionName($controllerActionName);
	}

	/**
	 * The widget identifier is unique on the current page, and is used
	 * in the URI as a namespace for the widget's arguments.
	 *
	 * @return string the widget identifier for this widget
	 * @return void
	 */
	private function initializeWidgetIdentifier() {
		$widgetIdentifier = ($this->hasArgument('widgetId') ? $this->arguments['widgetId'] : strtolower(str_replace('\\', '-', get_class($this))));
		$this->widgetContext->setWidgetIdentifier($widgetIdentifier);
	}

	/**
	 * Resets the ViewHelper state by creating a fresh WidgetContext
	 *
	 * @return void
	 */
	public function resetState() {
		if ($this->ajaxWidget) {
			$this->widgetContext = $this->objectManager->get('TYPO3\Fluid\Core\Widget\WidgetContext');
		}
	}

}

?>