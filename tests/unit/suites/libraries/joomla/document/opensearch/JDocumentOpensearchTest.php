<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/document/opensearch/opensearch.php';

/**
 * Test class for JDocumentOpensearch.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentOpensearchTest extends TestCase
{
	/**
	 * @var  JDocumentOpensearch
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();

		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['SCRIPT_NAME'] = '';

		JFactory::$application = $this->getMockCmsApp();
		JFactory::$config = $this->getMockConfig();

		$this->object = new JDocumentOpensearch;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();
	}

	/**
	 * Test...
	 *
	 * @return  void
	 */
	public function testRender()
	{
		$this->object->setShortName('ShortName');
		$this->object->setDescription('Description');

		$item = new JOpenSearchUrl;
		$item->template = 'http://www.example.com';

		$item2 = new JOpenSearchUrl;
		$item2->template = 'http://www.example.com?format=feed';
		$item2->type = 'application/rss+xml';
		$item2->rel = 'suggestions';

		$this->object->addUrl($item);
		$this->object->addUrl($item2);

		$expected = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL .
			'<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"><ShortName>ShortName</ShortName>' .
			'<Description>Description</Description><InputEncoding>UTF-8</InputEncoding>' .
			'<Url type="application/opensearchdescription+xml" rel="self" template="http://mydomain.com/index.php"/>' .
			'<Url type="text/html" template="http://www.example.com"/>' .
			'<Url type="application/rss+xml" rel="suggestions" template="http://www.example.com?format=feed"/>' .
			'</OpenSearchDescription>' . PHP_EOL;

		$this->assertXmlStringEqualsXmlString(
			$this->object->render(),
			$expected
		);
	}
}
