<?php
namespace F3\Fluid\ViewHelpers;

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
 * Returns translated message using source message or key ID.
 *
 * Also replaces all placeholders with formatted versions of provided values.
 *
 * = Examples =
 *
 * <code title="Default parameters">
 * <f:translate>Untranslated label</f:translate>
 * </code>
 * <output>
 * translation of the label "Untranslated label"
 * </output>
 *
 * <code title="Custom source and locale">
 * <f:translate source="SomeLabelsCatalog" locale="de_DE">Untranslated label</f:translate>
 * </code>
 * <output>
 * translation of the label "Untranslated label" from custom source "SomeLabelsCatalog" and locale "de_DE"
 * </output>
 *
 * <code title="Arguments">
 * <f:translate arguments="{0: 'foo', 1: '99.9'}">Untranslated {0} and {1,number}</f:translate>
 * </code>
 * <output>
 * translation of the label "Untranslated foo and 99.9"
 * </output>
 *
 * <code title="Translation by id">
 * <f:translate key="user.unregistered">Unregistered User</f:translate>
 * </code>
 * <output>
 * translation of label with the id "user.unregistered" and the default translation "Unregistered User"
 * </output>
 *
 * <code title="Inline notation">
 * {f:translate(key: 'someLabelId', default: 'default translation')}
 * </code>
 * <output>
 * translation of label with the id "someLabelId" and the default translation "default translation"
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class TranslateViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \F3\FLOW3\I18n\Translator
	 */
	protected $translator;

	/**
	 * @param \F3\FLOW3\I18n\Translator $translator
	 * @return void
	 * @author Karol Gusak <firstname@lastname.eu>
	 */
	public function injectTranslator(\F3\FLOW3\I18n\Translator $translator) {
		$this->translator = $translator;
	}

	/**
	 * Renders the translated label.
	 *
	 * Replaces all placeholders with corresponding values if they exist in the
	 * translated label.
	 *
	 * @param string $key Id to use for finding translation
	 * @param string $default if $key is not specified or could not be resolved, this value is used. If this argument is not set, child nodes will be used to render the default
	 * @param array $arguments Numerically indexed array of values to be inserted into placeholders
	 * @param string $source Name of file with translations
	 * @param mixed $quantity A number to find plural form for (float or int), NULL to not use plural forms
	 * @param string $locale An identifier of locale to use (NULL for use the default locale)
	 * @return string Translated label or source label / ID key
	 * @author Karol Gusak <firstname@lastname.eu>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function render($key = NULL, $default = NULL, array $arguments = array(), $source = 'Main', $quantity = NULL, $locale = NULL) {
		$localeObject = NULL;
		if ($locale !== NULL) {
			try {
				$localeObject = new \F3\FLOW3\I18n\Locale($locale);
			} catch (\F3\FLOW3\I18n\Exception\InvalidLocaleIdentifierException $e) {
				throw new \F3\Fluid\Core\ViewHelper\Exception('"' . $locale . '" is not a valid locale identifier.' , 1279815885);
			}
		}
		$originalLabel = $default !== NULL ? $default : $this->renderChildren();

		if ($key === NULL) {
			return $this->translator->translateByOriginalLabel($originalLabel, $source, $arguments, $quantity, $localeObject);
		} else {
			// @todo return $originalLabel if $key does not exist
			// like this? if result === $key -> $originalLabel
			return $this->translator->translateById($key, $source, $arguments, $quantity, $localeObject);
		}
	}
}

?>
