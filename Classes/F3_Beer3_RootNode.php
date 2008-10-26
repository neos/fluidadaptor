<?php
declare(ENCODING = 'utf-8');
namespace F3::Beer3;

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
 * @package Beer3
 * @version $Id:$
 */
/**
 * Root node
 *
 * @package Beer3
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 */
class RootNode extends F3::Beer3::AbstractNode {
	
	/**
	 * Evaluate the root node, by evaluating the subtree.
	 * 
	 * @param F3::Beer3::Context $context Context to be used
	 * @return object Evaluated subtree
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function evaluate(F3::Beer3::Context $context) {
		$this->context = $context;
		$text = $this->evaluateChildNodes();
		return $text;
	}
}


?>