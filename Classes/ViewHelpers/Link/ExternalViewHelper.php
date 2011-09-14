<?php
namespace TYPO3\Fluid\ViewHelpers\Link;

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
 * A view helper for creating links to external targets.
 *
 * = Examples =
 *
 * <code>
 * <f:link.external uri="http://www.typo3.org" target="_blank">external link</f:link.external>
 * </code>
 * <output>
 * <a href="http://www.typo3.org" target="_blank">external link</a>
 * </output>
 *
 * <code title="custom default scheme">
 * <f:link.external uri="typo3.org" defaultScheme="ftp">external ftp link</f:link.external>
 * </code>
 * <output>
 * <a href="ftp://typo3.org">external ftp link</a>
 * </output>
 *
 * @api
 * @scope prototype
 */
class ExternalViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'a';

	/**
	 * Initialize arguments
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @api
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('name', 'string', 'Specifies the name of an anchor');
		$this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document');
		$this->registerTagAttribute('rev', 'string', 'Specifies the relationship between the linked document and the current document');
		$this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
	}

	/**
	 * @param string $uri the URI that will be put in the href attribute of the rendered link tag
	 * @param string $defaultScheme scheme the href attribute will be prefixed with if specified $uri does not contain a scheme already
	 * @return string Rendered link
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render($uri, $defaultScheme = 'http') {
		$scheme = parse_url($uri, PHP_URL_SCHEME);
		if ($scheme === NULL && $defaultScheme !== '') {
			$uri = $defaultScheme . '://' . $uri;
		}
		$this->tag->addAttribute('href', $uri);
		$this->tag->setContent($this->renderChildren());
		$this->tag->forceClosingTag(TRUE);

		return $this->tag->render();
	}
}


?>
