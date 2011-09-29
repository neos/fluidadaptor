<?php
namespace TYPO3\Fluid\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Else-Branch of a condition. Only has an effect inside of "If". See the If-ViewHelper for documentation.
 * 
 * @see TYPO3\Fluid\ViewHelpers\IfViewHelper
 *
 * @api
 * @scope prototype
 */
class ElseViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @return string the rendered string
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @api
	 */
	public function render() {
		return $this->renderChildren();
	}
}

?>
