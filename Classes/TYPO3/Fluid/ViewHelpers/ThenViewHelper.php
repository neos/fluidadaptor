<?php
namespace TYPO3\Fluid\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * "THEN" -> only has an effect inside of "IF". See If-ViewHelper for documentation.
 *
 * @see \TYPO3\Fluid\ViewHelpers\IfViewHelper
 * @api
 */
class ThenViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Just render everything.
	 *
	 * @return string the rendered string
	 * @api
	 */
	public function render() {
		return $this->renderChildren();
	}
}

?>