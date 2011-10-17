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

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * View helper which creates a <base href="..."></base> tag. The Base URI
 * is taken from the current request.
 * In FLOW3, you should always include this ViewHelper to make the links work.
 *
 * = Examples =
 *
 * <code title="Example">
 * <f:base />
 * </code>
 * <output>
 * <base href="http://yourdomain.tld/" />
 * (depending on your domain)
 * </output>
 *
 * @api
 * @FLOW3\Scope("prototype")
 */
class BaseViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render the "Base" tag by outputting $request->getBaseUri()
	 *
	 * Note: renders as <base></base>, because IE6 will else refuse to display
	 * the page...
	 *
	 * @return string "base"-Tag.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @api
	 */
	public function render() {
		return '<base href="' . $this->controllerContext->getRequest()->getBaseUri() . '" />';
	}
}

?>
