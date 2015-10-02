<?php
namespace TYPO3\Fluid\View\Fixture;

/*
 * This file is part of the TYPO3.Fluid package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

/**
 * [Enter description here]
 *
 */
class TransparentSyntaxTreeNode extends \TYPO3\Fluid\Core\Parser\SyntaxTree\AbstractNode
{
    public $variableContainer;

    public function evaluate(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext)
    {
    }
}
