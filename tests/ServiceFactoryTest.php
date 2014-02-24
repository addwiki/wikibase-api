<?php

namespace Wikibase\Api\Test;

use Wikibase\Api\ServiceFactory;

/**
 * @covers Wikibase\Api\ServiceFactory
 */
class ServiceFactoryTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new ServiceFactory( $this->getMockApi() );
		$this->assertTrue( true );
	}

	public function testNewRevisionRepo() {
		$factory = new ServiceFactory( $this->getMockApi() );
		$repo = $factory->newRevisionRepo();
		$this->assertInstanceOf( '\Wikibase\Api\Service\RevisionRepo', $repo );
	}

} 