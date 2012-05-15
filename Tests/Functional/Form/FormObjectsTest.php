<?php
namespace TYPO3\Fluid\Tests\Functional\Form;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Http\Request;
use TYPO3\FLOW3\Http\Uri;
use TYPO3\FLOW3\Mvc\ActionRequest;

/**
 * Testcase for Standalone View
 *
 * @group large
 */
class FormObjectsTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	protected $testableHttpEnabled = TRUE;

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \TYPO3\FLOW3\Http\Client\Browser
	 */
	protected $browser;

	/**
	 * Initializer
	 */
	public function setUp() {
		parent::setUp();

		$route = new \TYPO3\FLOW3\Mvc\Routing\Route();
		$route->setUriPattern('test/fluid/formobjects(/{@action})');
		$route->setDefaults(array(
			'@package' => 'TYPO3.Fluid',
			'@subpackage' => 'Tests\Functional\Form\Fixtures',
			'@controller' => 'Form',
			'@action' => 'index',
			'@format' =>'html'
		));
		$route->setAppendExceedingArguments(TRUE);
		$this->router->addRoute($route);
	}

	/**
	 * @test
	 */
	public function objectIsCreatedCorrectly() {
		$this->browser->request('http://localhost/test/fluid/formobjects');
		$form = $this->browser->getForm();

		$form['post']['name']->setValue('Egon Olsen');
		$form['post']['email']->setValue('test@typo3.org');

		$response = $this->browser->submit($form);
		$this->assertSame('Egon Olsen|test@typo3.org', $response->getContent());
	}

	/**
	 * @test
	 */
	public function formIsRedisplayedIfValidationErrorsOccur() {
		$page = $this->browser->request('http://localhost/test/fluid/formobjects');
		$form = $this->browser->getForm();

		$form['post']['name']->setValue('Egon Olsen');
		$form['post']['email']->setValue('test_noValidEmail');

		$this->browser->submit($form);
		$form = $this->browser->getForm();
		$this->assertSame('Egon Olsen', $form['post']['name']->getValue());
		$this->assertSame('test_noValidEmail', $form['post']['email']->getValue());
		$this->assertSame('f3-form-error', $this->browser->getCrawler()->filterXPath('//*[@id="email"]')->attr('class'));

		$form['post']['email']->setValue('another@email.org');

		$response = $this->browser->submit($form);
		$this->assertSame('Egon Olsen|another@email.org', $response->getContent());
	}

	/**
	 * @test
	 */
	public function objectIsNotCreatedAnymoreIfHmacHasBeenTampered() {
		$this->browser->request('http://localhost/test/fluid/formobjects');
		$form = $this->browser->getForm();

		$form['__trustedProperties']->setValue($form['__trustedProperties']->getValue() . 'a');
		$this->browser->submit($form);

		$this->assertSame('500 Internal Server Error', $this->browser->getLastResponse()->getStatus());
	}

	/**
	 * @test
	 */
	public function objectIsNotCreatedAnymoreIfIdentityFieldHasBeenAdded() {
		$this->browser->request('http://localhost/test/fluid/formobjects');
		$form = $this->browser->getForm();

		$identityFieldDom = dom_import_simplexml(simplexml_load_string('<input type="text" name="post[__identity]" value="someIdentity" />'));
		$form->set(new \Symfony\Component\DomCrawler\Field\InputFormField($identityFieldDom));

		$this->browser->submit($form);

		$this->assertSame('500 Internal Server Error', $this->browser->getLastResponse()->getStatus());
	}

	/**
	 * @test
	 */
	public function objectIsNotCreatedAnymoreIfNewFieldHasBeenAdded() {
		$this->browser->request('http://localhost/test/fluid/formobjects');
		$form = $this->browser->getForm();

		$identityFieldDom = dom_import_simplexml(simplexml_load_string('<input type="text" name="post[someProperty]" value="someValue" />'));
		$form->set(new \Symfony\Component\DomCrawler\Field\InputFormField($identityFieldDom));

		$this->browser->submit($form);

		$this->assertSame('500 Internal Server Error', $this->browser->getLastResponse()->getStatus());
	}

	/**
	 * @test
	 */
	public function objectIsNotCreatedAnymoreIfHmacIsRemoved() {
		$this->browser->request('http://localhost/test/fluid/formobjects');
		$form = $this->browser->getForm();

		unset($form['__trustedProperties']);
		$this->browser->submit($form);

		$this->assertSame('500 Internal Server Error', $this->browser->getLastResponse()->getStatus());
	}

	/**
	 * @test
	 */
	public function objectCanBeModified() {
		$postIdentifier = $this->setupDummyPost();

		$this->browser->request('http://localhost/test/fluid/formobjects/edit?fooPost=' . $postIdentifier);
		$form = $this->browser->getForm();

		$this->assertSame('myName', $form['post']['name']->getValue());

		$form['post']['name']->setValue('Hello World');
		$response = $this->browser->submit($form);
		$this->assertSame('Hello World|foo@bar.org', $response->getContent());
	}

	/**
	 * @test
	 */
	public function objectIsNotModifiedAnymoreIfHmacHasBeenManipulated() {
		$postIdentifier = $this->setupDummyPost();

		$this->browser->request('http://localhost/test/fluid/formobjects/edit?fooPost=' . $postIdentifier);
		$form = $this->browser->getForm();

		$form['__trustedProperties']->setValue($form['__trustedProperties']->getValue() . 'a');
		$this->browser->submit($form);

		$this->assertSame('500 Internal Server Error', $this->browser->getLastResponse()->getStatus());
	}

	/**
	 * @test
	 */
	public function objectIsNotModifiedAnymoreIfIdentityFieldHasBeenRemoved() {
		$postIdentifier = $this->setupDummyPost();

		$this->browser->request('http://localhost/test/fluid/formobjects/edit?fooPost=' . $postIdentifier);
		$form = $this->browser->getForm();
		$form->remove('post[__identity]');

		$this->browser->submit($form);

		$this->assertSame('500 Internal Server Error', $this->browser->getLastResponse()->getStatus());
	}

	/**
	 * @test
	 */
	public function objectIsNotModifiedAnymoreIfNewFieldHasBeenAdded() {
		$postIdentifier = $this->setupDummyPost();

		$this->browser->request('http://localhost/test/fluid/formobjects/edit?fooPost=' . $postIdentifier);
		$form = $this->browser->getForm();

		$emailFieldDom = dom_import_simplexml(simplexml_load_string('<input type="text" name="post[email]" value="some@email.address" />'));
		$form->set(new \Symfony\Component\DomCrawler\Field\InputFormField($emailFieldDom));

		$this->browser->submit($form);

		$this->assertSame('500 Internal Server Error', $this->browser->getLastResponse()->getStatus());
	}

	/**
	 * @test
	 */
	public function objectIsNotModifiedAnymoreIfHmacIsRemoved() {
		$postIdentifier = $this->setupDummyPost();

		$this->browser->request('http://localhost/test/fluid/formobjects/edit?fooPost=' . $postIdentifier);
		$form = $this->browser->getForm();

		unset($form['__trustedProperties']);
		$this->browser->submit($form);

		$this->assertSame('500 Internal Server Error', $this->browser->getLastResponse()->getStatus());
	}

	/**
	 * @return string UUID of the dummy post
	 */
	protected function setupDummyPost() {
		$post = new Fixtures\Domain\Model\Post();
		$post->setEmail('foo@bar.org');
		$post->setName('myName');
		$this->persistenceManager->add($post);
		$postIdentifier = $this->persistenceManager->getIdentifierByObject($post);
		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();

		return $postIdentifier;
	}
}
?>