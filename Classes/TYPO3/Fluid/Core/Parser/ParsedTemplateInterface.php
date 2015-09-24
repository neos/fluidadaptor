<?php
namespace TYPO3\Fluid\Core\Parser;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */

use TYPO3\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * This interface is returned by \TYPO3\Fluid\Core\Parser\TemplateParser->parse()
 * method and is a parsed template
 */
interface ParsedTemplateInterface
{
    /**
     * Render the parsed template with rendering context
     *
     * @param RenderingContextInterface $renderingContext The rendering context to use
     * @return string Rendered string
     */
    public function render(RenderingContextInterface $renderingContext);

    /**
     * Returns a variable container used in the PostParse Facet.
     *
     * @return \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer
     * @todo remove
     */
    public function getVariableContainer();

    /**
     * Returns the name of the layout that is defined within the current template via <f:layout name="..." />
     * If no layout is defined, this returns NULL
     * This requires the current rendering context in order to be able to evaluate the layout name
     *
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public function getLayoutName(RenderingContextInterface $renderingContext);

    /**
     * Returns TRUE if the current template has a template defined via <f:layout name="..." />
     *
     * @see getLayoutName()
     * @return boolean
     */
    public function hasLayout();

    /**
     * If the template contains constructs which prevent the compiler from compiling the template
     * correctly, isCompilable() will return FALSE.
     *
     * @return boolean TRUE if the template can be compiled
     */
    public function isCompilable();

    /**
     * @return boolean TRUE if the template is already compiled, FALSE otherwise
     */
    public function isCompiled();
}
