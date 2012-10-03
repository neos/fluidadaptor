<?php
namespace TYPO3\Fluid\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException;
use TYPO3\Flow\I18n;

/**
 * Formats a given float to a currency representation.
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <f:format.currency>123.456</f:format.currency>
 * </code>
 * <output>
 * 123,46
 * </output>
 *
 * <code title="All parameters">
 * <f:format.currency currencySign="$" decimalSeparator="." thousandsSeparator=",">54321</f:format.currency>
 * </code>
 * <output>
 * 54,321.00 $
 * </output>
 *
 * <code title="Inline notation">
 * {someNumber -> f:format.currency(thousandsSeparator: ',', currencySign: '€')}
 * </code>
 * <output>
 * 54,321,00 €
 * (depending on the value of {someNumber})
 * </output>
 *
 * <code title="Inline notation with current locale used">
 * {someNumber -> f:format.currency(currencySign: '€', forceLocale: true)}
 * </code>
 * <output>
 * 54.321,00 €
 * (depending on the value of {someNumber} and the current locale)
 * </output>
 *
 * <code title="Inline notation with specific locale used">
 * {someNumber -> f:format.currency(currencySign: 'EUR', forceLocale: 'de_DE')}
 * </code>
 * <output>
 * 54.321,00 EUR
 * (depending on the value of {someNumber})
 * </output>
 *
 * @api
 */
class CurrencyViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Formatter\NumberFormatter
	 */
	protected $numberFormatter;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $localizationService;

	/**
	 * @param string $currencySign (optional) The currency sign, eg $ or €.
	 * @param string $decimalSeparator (optional) The separator for the decimal point.
	 * @param string $thousandsSeparator (optional) The thousands separator.
	 * @param mixed $forceLocale Whether if, and what, Locale should be used; overriding $decimal- and $thousandsSeparator. May be boolean, string or \TYPO3\Flow\I18n\Locale
	 *
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @return string the formatted amount.
	 * @api
	 */
	public function render($currencySign = '', $decimalSeparator = ',', $thousandsSeparator = '.', $forceLocale = NULL) {
		$stringToFormat = $this->renderChildren();

		if ($forceLocale !== NULL) {
			if ($currencySign === '') {
				throw new InvalidVariableException('Using the Locale requires a currencySign.', 1326378320);
			}
			$output = $this->renderUsingLocale($stringToFormat, $forceLocale, $currencySign);
		} else {
			$output = number_format($stringToFormat, 2, $decimalSeparator, $thousandsSeparator);
			if ($currencySign !== '') {
				$output .= ' ' . $currencySign;
			}
		}
		return $output;
	}

	/**
	 * @param mixed $stringToFormat
	 * @param mixed $locale string or boolean or \TYPO3\Flow\I18n\Locale
	 * @param string $currencySign
	 *
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @return string
	 */
	protected function renderUsingLocale($stringToFormat, $locale, $currencySign) {
		if ($locale instanceof I18n\Locale) {
			$useLocale = $locale;
		} elseif (is_string($locale)) {
			try {
				$useLocale = new I18n\Locale($locale);
			} catch (I18n\Exception $exception) {
				throw new InvalidVariableException('"' . $locale . '" is not a valid locale identifier.' , 1342610148, $exception);
			}
		} else {
			$useLocale = $this->localizationService->getConfiguration()->getCurrentLocale();
		}

		return $this->numberFormatter->formatCurrencyNumber($stringToFormat, $useLocale, $currencySign);
	}
}

?>