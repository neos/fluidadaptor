<?php
namespace F3\Fluid\ViewHelpers\Format;

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
 * Formats a \DateTime object.
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <f:format.date>{dateObject}</f:format.date>
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the current date)
 * </output>
 *
 * <code title="Custom date format">
 * <f:format.date format="H:i">{dateObject}</f:format.date>
 * </code>
 * <output>
 * 01:23
 * (depending on the current time)
 * </output>
 *
 * <code title="strtotime string">
 * <f:format.date format="d.m.Y - H:i:s">+1 week 2 days 4 hours 2 seconds</f:format.date>
 * </code>
 * <output>
 * 13.12.1980 - 21:03:42
 * (depending on the current time, see http://www.php.net/manual/en/function.strtotime.php)
 * </output>
 *
 * <code title="output date from unix timestamp">
 * <f:format.date format="d.m.Y - H:i:s">@{someTimestamp}</f:format.date>
 * </code>
 * <output>
 * 13.12.1980 - 21:03:42
 * (depending on the current time. Don't forget the "@" in front of the timestamp see http://www.php.net/manual/en/function.strtotime.php)
 * </output>
 *
 * <code title="Inline notation">
 * {f:format.date(date: dateObject)}
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the value of {dateObject})
 * </output>
 *
 * <code title="Inline notation (2nd variant)">
 * {dateObject -> f:format.date()}
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the value of {dateObject})
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class DateViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render the supplied DateTime object as a formatted date.
	 *
	 * @param mixed $date either a \DateTime object or a string that is accepted by \DateTime constructor
	 * @param string $format Format String which is taken to format the Date/Time
	 * @return string Formatted date
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render($date = NULL, $format = 'Y-m-d') {
		if ($date === NULL) {
			$date = $this->renderChildren();
			if ($date === NULL) {
				return '';
			}
		}
		if (!$date instanceof \DateTime) {
			try {
				$date = new \DateTime($date);
			} catch (\Exception $exception) {
				throw new \F3\Fluid\Core\ViewHelper\Exception('"' . $date . '" could not be parsed by \DateTime constructor.', 1241722579);
			}
		}
		return $date->format($format);
	}
}
?>