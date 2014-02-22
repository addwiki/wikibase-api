<?php

namespace Wikibase\Api\Test\Repos;

use Wikibase\Api\Repos\EntityRevisionRepo;

/**
 * @covers Wikibase\Api\Repos\EntityRevisionRepo
 */
class EntityRevisionRepoTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function getMockDeserializer() {
		$mock = $this->getMockBuilder( '\Deserializers\Deserializer' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new EntityRevisionRepo( $this->getMockApi(), $this->getMockDeserializer() );
		$this->assertTrue( true );
	}

} 