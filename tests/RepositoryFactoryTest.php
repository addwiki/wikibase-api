<?php

namespace Wikibase\Api\Test;

use Wikibase\Api\RepositoryFactory;

/**
 * @covers Wikibase\Api\RepositoryFactory
 */
class RepositoryFactoryTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new RepositoryFactory( $this->getMockApi() );
		$this->assertTrue( true );
	}

	public function testNewRevisionRepo() {
		$factory = new RepositoryFactory( $this->getMockApi() );
		$repo = $factory->newRevisionRepo();
		$this->assertInstanceOf( '\Wikibase\Api\RevisionRepo', $repo );
	}

} 