<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Core\ViewHelper\Facets;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @package Fluid
 * @subpackage Core
 * @version $Id$
 */

/**
 * Post Parse Facet. Your view helper should implement this if you want a callback
 * to be called directly after the syntax tree node corresponding to this view helper has been built.
 *
 * In the callback, it is possible to store some variables inside the parseVariableContainer (which is different from the runtime variable container!).
 * This implicates that you usually have to adjust the \F3\Fluid\View\TemplateView in case you implement this facet.
 *
 * Normally, this facet is not needed, except in really really rare cases.
 *
 * @package Fluid
 * @subpackage Core
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @internal
 */
interface PostParseInterface {

	/**
	 * Callback which is called directly after the corresponding syntax tree node to this view helper has been built.
	 * This is a parse-time callback, which does not change the rendering of a view helper.
	 *
	 * You can store some data inside the variableContainer given here, which can be used f.e. inside the TemplateView.
	 *
	 * @param \F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode $syntaxTreeNode The current node in the syntax tree corresponding to this view helper.
	 * @param array $viewHelperArguments View helper arguments as an array of SyntaxTrees. If you really need an argument, make sure to call $viewHelperArguments[$argName]->render(...)!
	 * @param VariableContainer $variableContainer Variable container you can use to pass on some variables to the view.
	 * @return void
	 * @internal
	 */
	static public function postParseEvent(\F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode $syntaxTreeNode, array $viewHelperArguments, \F3\Fluid\Core\ViewHelper\TemplateVariableContainer $variableContainer);

}

?>