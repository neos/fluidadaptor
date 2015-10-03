<?php
namespace TYPO3\Fluid\Core\Parser\SyntaxTree;

/*
 * This file is part of the TYPO3.Fluid package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Fluid\Core\Parser;
use TYPO3\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Numeric Syntax Tree Node - is a container for numeric values.
 *
 */
class NumericNode extends AbstractNode
{
    /**
     * Contents of the numeric node
     * @var number
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param string|number $value value to store in this numericNode
     * @throws Parser\Exception
     */
    public function __construct($value)
    {
        if (!is_numeric($value)) {
            throw new Parser\Exception('Numeric node requires an argument of type number, "' . gettype($value) . '" given.');
        }
        $this->value = $value + 0;
    }

    /**
     * Return the value associated to the syntax tree.
     *
     * @param RenderingContextInterface $renderingContext
     * @return number the value stored in this node/subtree.
     */
    public function evaluate(RenderingContextInterface $renderingContext)
    {
        return $this->value;
    }

    /**
     * Getter for value
     *
     * @return number The value of this node
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * NumericNode does not allow adding child nodes, so this will always throw an exception.
     *
     * @param NodeInterface $childNode The sub node to add
     * @throws Parser\Exception
     * @return void
     */
    public function addChildNode(NodeInterface $childNode)
    {
        throw new Parser\Exception('Numeric nodes may not contain child nodes, tried to add "' . get_class($childNode) . '".');
    }
}
