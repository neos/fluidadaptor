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
 * Declares new variables which are aliases of other variables.
 * Takes a "map"-Parameter which is an associative array which defines the shorthand mapping.
 *
 * The variables are only declared inside the <f:alias>...</f:alias>-tag. After the
 * closing tag, all declared variables are removed again.
 *
 * = Examples =
 *
 * <code title="Single alias">
 * <f:alias map="{x: 'foo'}">{x}</f:alias>
 * </code>
 * <output>
 * foo
 * </output>
 *
 * <code title="Multiple mappings">
 * <f:alias map="{x: foo.bar.baz, y: foo.bar.baz.name}">
 *   {x.name} or {y}
 * </f:alias>
 * </code>
 * <output>
 * [name] or [name]
 * depending on {foo.bar.baz}
 * </output>
 *
 * @api
 */
class AliasViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Renders alias
	 *
	 * @param array $map array that specifies which variables should be mapped to which alias
	 * @return string Rendered string
	 * @api
	 */
	public function render(array $map) {
		foreach ($map as $aliasName => $value) {
			$this->templateVariableContainer->add($aliasName, $value);
		}
		$output = $this->renderChildren();
		foreach ($map as $aliasName => $value) {
			$this->templateVariableContainer->remove($aliasName);
		}
		return $output;
	}
}

?>