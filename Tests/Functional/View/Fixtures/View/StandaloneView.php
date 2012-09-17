<?php
namespace TYPO3\Fluid\Tests\Functional\View\Fixtures\View;

	/*                                                                        *
	 * This script belongs to the FLOW3 package "Fluid".                      *
	 *                                                                        *
	 * It is free software; you can redistribute it and/or modify it under    *
	 * the terms of the GNU Lesser General Public License, either version 3   *
	 * of the License, or (at your option) any later version.                 *
	 *                                                                        *
	 * The TYPO3 project - inspiring people to share!                         *
	 *                                                                        */

/**
 * Extended StandaloneView for testing purposes
 */
class StandaloneView extends \TYPO3\Fluid\View\StandaloneView {

	protected $fileIdentifierPrefix = '';

	/**
	 * Constructor
	 *
	 * @param \TYPO3\FLOW3\Mvc\ActionRequest $request The current action request. If none is specified it will be created from the environment.
	 * @param string $fileIdentifierPrefix
	 */
	public function __construct(\TYPO3\FLOW3\Mvc\ActionRequest $request = NULL, $fileIdentifierPrefix = '') {
		$this->request = $request;
		$this->fileIdentifierPrefix = $fileIdentifierPrefix;
	}


	protected function createIdentifierForFile($pathAndFilename, $prefix) {
		$prefix = $this->fileIdentifierPrefix . $prefix;
		return parent::createIdentifierForFile($pathAndFilename, $prefix);
	}
}
?>