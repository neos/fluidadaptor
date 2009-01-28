<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers;

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
 * @subpackage ViewHelpers
 * @version $Id:$
 */
/**
 * Loop view helper
 *
 * @package Fluid
 * @subpackage ViewHelpers
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 */
class ForViewHelper extends \F3\Fluid\Core\AbstractViewHelper {
	
	/**
	 * Arguments initialization
	 * 
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function initializeArguments() {
		$this->registerArgument('each', 'array', 'The array which is iterated over.', TRUE);
		$this->registerArgument('as', 'string', 'Name of the variable where each array element is bound to.', TRUE);
	}
	
	/**
	 * Render.
	 *
	 * @return string Rendered string
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function render() {
		$out = '';
		foreach ($this->arguments['each'] as $singleElement) {
			$this->variableContainer->add($this->arguments['as'], $singleElement);
			$out .= $this->renderChildren();
			$this->variableContainer->remove($this->arguments['as']);
		}
		return $out;
	}
}

?>
