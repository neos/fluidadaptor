<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers\Link;

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
 * Email link view helper.
 * Generates an email link.
 *
 * = Examples =
 *
 * <code title="basic email link">
 * <f:link.email email="foo@bar.tld" />
 * </code>
 * <output>
 * <a href="mailto:foo@bar.tld">foo@bar.tld</a>
 * </output>
 *
 * <code title="Email link with custom linktext">
 * <f:link.email email="foo@bar.tld">some custom content</f:emaillink>
 * </code>
 * <output>
 * <a href="mailto:foo@bar.tld">some custom content</a>
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class EmailViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var	string
	 */
	protected $tagName = 'a';

	/**
	 * Arguments initialization
	 *
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('name', 'string', 'Specifies the name of an anchor');
		$this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document');
		$this->registerTagAttribute('rev', 'string', 'Specifies the relationship between the linked document and the current document');
		$this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
	}

	/**
	 * @param string $email The email address to be turned into a link.
	 * @return string Rendered email link
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render($email) {
		$linkHref = 'mailto:' . $email;
		$linkText = $email;
		$tagContent = $this->renderChildren();
		if ($tagContent !== NULL) {
			$linkText = $tagContent;
		}
		$this->tag->setContent($linkText);
		$this->tag->addAttribute('href', $linkHref);
		$this->tag->forceClosingTag(TRUE);

		return $this->tag->render();
	}
}


?>
