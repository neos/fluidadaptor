<?php
namespace TYPO3\Fluid\Tests\Functional\Form\Fixtures\Controller;

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
 * Controller for simple CRUD actions, to test Fluid forms in
 * combination with Property Mapping
 */
class FormController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * Display a start page
	 *
	 * @return void
	 */
	public function indexAction() {
	}

	/**
	 * @param \TYPO3\Fluid\Tests\Functional\Form\Fixtures\Domain\Model\Post $post
	 * @return string
	 */
	public function createAction(\TYPO3\Fluid\Tests\Functional\Form\Fixtures\Domain\Model\Post $post) {
		return $post->getName() . '|' . $post->getEmail();
	}

	/**
	 * We deliberately use a different variable name in the index action and the create action; as the same variable name is not required!
	 *
	 * @param \TYPO3\Fluid\Tests\Functional\Form\Fixtures\Domain\Model\Post $fooPost
	 * @return void
	 */
	public function editAction(\TYPO3\Fluid\Tests\Functional\Form\Fixtures\Domain\Model\Post $fooPost = NULL) {
		$this->view->assign('fooPost', $fooPost);

	}

	/**
	 * @param \TYPO3\Fluid\Tests\Functional\Form\Fixtures\Domain\Model\Post $post
	 * @return string
	 */
	public function updateAction(\TYPO3\Fluid\Tests\Functional\Form\Fixtures\Domain\Model\Post $post) {
		return $post->getName() . '|' . $post->getEmail();
	}
}
?>