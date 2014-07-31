<?php

namespace Wikibase\Api\Test;

use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\WikibaseFactory;

class IntegrationTestBase extends \PHPUnit_Framework_TestCase {

	/**
	 * @var WikibaseFactory
	 */
	protected $factory;

	protected function setUp() {
		parent::setUp();
		$this->factory = new WikibaseFactory(
			new MediawikiApi( 'http://localhost/w/api.php' )
		);
	}

	// Needed to stop phpunit complaining
	public function testFactory() {
		$this->assertInstanceOf( 'Wikibase\Api\WikibaseFactory', $this->factory );
	}

	protected function tearDown() {
		parent::tearDown();
		unset( $this->factory );
	}

} 