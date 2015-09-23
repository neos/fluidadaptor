<?php
namespace TYPO3\Fluid\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Fluid\Core\ViewHelper;

/**
 * Case view helper that is only usable within the SwitchViewHelper.
 * @see \TYPO3\Fluid\ViewHelpers\SwitchViewHelper
 *
 * @api
 */
class CaseViewHelper extends AbstractViewHelper
{
    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @param mixed $value
     * @return string the contents of this view helper if $value equals the expression of the surrounding switch view helper, otherwise an empty string
     * @throws ViewHelper\Exception
     * @api
     */
    public function render($value)
    {
        $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
        if (!$viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\SwitchViewHelper', 'switchExpression')) {
            throw new ViewHelper\Exception('The "case" View helper can only be used within a switch View helper', 1368112037);
        }
        $switchExpression = $viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\SwitchViewHelper', 'switchExpression');

        // non-type-safe comparison by intention
        if ($switchExpression == $value) {
            $viewHelperVariableContainer->addOrUpdate('TYPO3\Fluid\ViewHelpers\SwitchViewHelper', 'break', true);
            return $this->renderChildren();
        }
        return '';
    }
}
