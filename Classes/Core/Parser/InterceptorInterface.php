<?php
namespace TYPO3\Fluid\Core\Parser;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * An interceptor interface. Interceptors are used in the parsing stage to change
 * the syntax tree of a template, e.g. by adding viewhelper nodes.
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
interface InterceptorInterface {

	const INTERCEPT_OPENING_VIEWHELPER = 1;
	const INTERCEPT_CLOSING_VIEWHELPER = 2;
	const INTERCEPT_TEXT = 3;
	const INTERCEPT_OBJECTACCESSOR = 4;

	/**
	 * The interceptor can process the given node at will and must return a node
	 * that will be used in place of the given node.
	 *
	 * @param \TYPO3\Fluid\Core\Parser\SyntaxTree\NodeInterface $node
	 * @param integer $interceptorPosition One of the INTERCEPT_* constants for the current interception point
	 * @param \TYPO3\Fluid\Core\Parser\ParsingState $parsingState the parsing state
	 * @return \TYPO3\Fluid\Core\Parser\SyntaxTree\NodeInterface
	 */
	public function process(\TYPO3\Fluid\Core\Parser\SyntaxTree\NodeInterface $node, $interceptorPosition, \TYPO3\Fluid\Core\Parser\ParsingState $parsingState);

	/**
	 * The interceptor should define at which interception positions it wants to be called.
	 *
	 * @return array Array of INTERCEPT_* constants
	 */
	public function getInterceptionPoints();
}
?>