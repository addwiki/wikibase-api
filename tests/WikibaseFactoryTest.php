<?php

namespace Wikibase\Api\Test;

use Wikibase\Api\WikibaseFactory;

/**
 * @covers Wikibase\Api\WikibaseFactory
 */
class WikibaseFactoryTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new WikibaseFactory( $this->getMockApi() );
		$this->assertTrue( true );
	}

	public function testNewRevisionGetter() {
		$factory = new WikibaseFactory( $this->getMockApi() );
		$getter = $factory->newRevisionGetter();
		$this->assertInstanceOf( '\Wikibase\Api\Service\RevisionGetter', $getter );
	}

} 