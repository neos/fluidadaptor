<?php
namespace TYPO3\Fluid\ViewHelpers\Widget;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryResultInterface;
use TYPO3\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 * Usage:
 * <f:input id="name" ... />
 * <f:widget.autocomplete for="name" objects="{posts}" searchProperty="author">
 *
 * Make sure to include jQuery and jQuery UI in the HTML, like that:
 *    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
 *    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
 *    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css" type="text/css" media="all" />
 *    <link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />
 *
 * @api
 */
class AutocompleteViewHelper extends AbstractWidgetViewHelper
{
    /**
     * @var bool
     */
    protected $ajaxWidget = true;

    /**
     * @Flow\Inject
     * @var Controller\AutocompleteController
     */
    protected $controller;

    /**
     *
     * @param QueryResultInterface $objects
     * @param string $for
     * @param string $searchProperty
     * @param array $configuration
     * @return string
     */
    public function render(QueryResultInterface $objects, $for, $searchProperty, array $configuration = array('limit' => 10))
    {
        return $this->initiateSubRequest();
    }
}
